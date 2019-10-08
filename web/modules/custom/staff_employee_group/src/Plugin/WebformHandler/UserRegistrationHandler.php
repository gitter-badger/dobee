<?php

namespace Drupal\staff_employee_group\Plugin\WebformHandler;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\profile\Entity\Profile;
use Drupal\user\Entity\User;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
    /**
     * @var User $user
     */
    // protected $user;

    /**     
     * @var Profile $profile
     */
    // protected $profile;

    /**
   * {@inheritdoc}
   */
    /*  public function __construct(array $configuration, $plugin_id, $plugin_definition, User $user, Profile $profile)
        {
            $this->user = $user;
            $this->profile = $profile;
        }

        public static function create(ContainerInterface $container,array $configuration, $plugin_id, $plugin_definition)
        {
            return new static(
                $configuration,
                $plugin_id,
                $plugin_definition,
                $container->get('entity_type.manager')->getStorage('user'),
                $container->get('entity_type.manager')->getStorage('profile')
            );
        } */
    public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE)
    {
        //TODO: Handle account creation
        $user = \Drupal\user\Entity\User::create();
        $user->setPassword($webform_submission->getElementData('password'));
        $user->setEmail($webform_submission->getElementData('email'));
        $user->setUsername($webform_submission->getElementData('email'));
        $new_user = $user->save();
        $this->profileSave($new_user, $webform_submission);
    }

    private function profileSave(int $uid, WebformSubmissionInterface $webform_submission)
    {
        $user_profile = $this->profile->loadByProperties([
            'uid' => $uid,
            'type' => 'employee'
        ]);
        \Drupal::logger('debug')->debug('HERE');
    }
}
