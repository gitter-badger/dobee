<?php

namespace Drupal\staff_employee_group\Plugin\WebformHandler;

use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform\Plugin\WebformHandlerBase;

/**
 * Webform example handler.
 *
 * @WebformHandler(
 *   id = "userRegistrationHandler",
 *   label = @Translation("User Registration"),
 *   category = @Translation("DOBEE."),
 *   description = @Translation("Handels the user registration"),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_IGNORED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_OPTIONAL,
 * )
 */
class UserRegistrationHandler extends WebformHandlerBase
{
    public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE)
    {

        \Drupal::logger('debug')->debug('HERE');
        //TODO: Handle account creation
        $user = \Drupal\user\Entity\User::create();
        $user->setPassword($webform_submission->get('password'));
        $user->setEmail($webform_submission->get('email'));
        $user->setUsername($webform_submission->get('email'));
        $new_user = $user->save();
    }
}
