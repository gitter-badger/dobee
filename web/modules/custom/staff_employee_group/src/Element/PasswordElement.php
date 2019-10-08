<?php

namespace Drupal\staff_employee_group\Element;

use Drupal\Core\Render\Element;
use Drupal\Core\Render\Element\FormElement;
use Drupal\Core\Form\FormStateInterface;
/**
 * @FormElement("dobee_password_element")
 */
class PasswordElement extends FormElement{

    /**
    * {@inheritdoc}
    */
    public function getInfo() {
        $class = get_class($this);
        return [
        '#input' => TRUE,
        '#size' => 60,
        '#process' => [
            [$class,'processWebformElement'],
            [$class, 'processAjaxForm'],
        ],
        '#element_validate' => [
            [$class, 'validateWebformElement'],
        ],
        '#value_callback' => [
            [$class,'dobeePasswordValueCallback']
        ]
        ];
    }


    public static function processWebformElement(&$element, FormStateInterface $form_state, &$complete_form) {
        $element['password'] = [
            '#title' => 'Password',
            '#type' => 'password'
        ];
        $element['dobee_re_password'] = [
            '#title' => 'Retype password',
            '#type' => 'password'
        ];

        return $element;
    }

    public static function validateWebformElement(&$element, FormStateInterface $form_state, &$complete_form)
    {
        $password = $form_state->getValue('password');
        $repassword = $form_state->getValue('dobee_re_password');

        if(empty($password) || $password != $repassword)
        {
            $form_state->setError($element,'Passwords do not match or are empty');
        }

        return $element;
    }

    /**
     * {@inheritdoc}
     */
    public static function dobeePasswordValueCallback(&$element, $input, FormStateInterface $form_state) {
        return FALSE;
    }
}

