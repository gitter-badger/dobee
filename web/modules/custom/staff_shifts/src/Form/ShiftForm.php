<?php

namespace Drupal\staff_shifts\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the shift entity edit forms.
 */
class ShiftForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => render($link)];

    if ($result == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('New shift %label has been created.', $message_arguments));
      $this->logger('staff_shifts')->notice('Created new shift %label', $logger_arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The shift %label has been updated.', $message_arguments));
      $this->logger('staff_shifts')->notice('Updated new shift %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.shift.canonical', ['shift' => $entity->id()]);
  }

}
