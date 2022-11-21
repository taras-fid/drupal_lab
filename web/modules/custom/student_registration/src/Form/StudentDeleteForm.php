<?php

namespace Drupal\student_registration\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Exception;

class StudentDeleteForm extends FormBase
{
  public function getFormId(): string
  {
    return 'student_delete_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array
  {
    $form['button'] = [
      '#type' => 'submit',
      '#value' => $this->t('Yes, delete')
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * @throws Exception
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    # TODO insert form info to database 'students'
    Drupal::database()
      ->delete('students')
      ->condition('id', Drupal::routeMatch()->getParameter('id'), '=')
      ->execute();

    $this->messenger()->addStatus($this->t('Data was successfully deleted from database.'));
    $this->redirect('student_registration.list')->send();
  }
}
