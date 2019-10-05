<?php

namespace Drupal\staff_employee_group\Validate;

use Drupal\Core\Form\FormStateInterface;

class UserEmailValidation 
{
    public static function validate (array &$element, FormStateInterface $form_state, array &$form)
    {
        $webform_key = $element['#webform_key'];
        $value = $form_state->getValue($webform_key);

        if(empty($value) || is_array($value))
            return;

        $user = user_load_by_mail($value);
        if(!empty($user))
        {
            $form_state->setError($element, 'A user with this email is already registered. Please go to login');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
}
