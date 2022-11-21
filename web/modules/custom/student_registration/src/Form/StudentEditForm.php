<?php

namespace Drupal\student_registration\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Exception;

class StudentEditForm extends FormBase
{
  public function getFormId(): string
  {
    return 'student_edit_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array
  {
    $object = Drupal::database()
      ->select('students', 's')
      ->condition('s.id', Drupal::routeMatch()->getParameter('id'), '=')
      ->fields('s', ['name', 'age', 'email', 'phone', 'average_mark'])
      ->execute()
      ->fetchAll()[0];
    $form['name'] = [
      '#type' => 'textfield',
      '#description' => $this->t('name text field'),
      '#required' => TRUE,
      '#default_value' => "$object->name"
    ];

    $form['age'] = [
      '#type' => 'number',
      '#description' => $this->t('age number field'),
      '#required' => TRUE,
      '#min' => 16,
      '#max' => 120,
      '#default_value' => $object->age
    ];

    $form['email'] = [
      '#type' => 'email',
      '#description' => $this->t('email field'),
      '#required' => TRUE,
      '#default_value' => "$object->email"
    ];

    $form['phone'] = [
      '#type' => 'tel',
      '#description' => $this->t('phone field (0961111111)'),
      '#default_value' => "$object->phone",
      '#required' => TRUE,
      '#attributes' => []
    ];

    $form['average_mark'] = [
      '#type' => 'number',
      '#step' => 0.01,
      '#description' => $this->t('average mark field'),
      '#required' => TRUE,
      '#default_value' => "$object->average_mark",
      '#min' => 0,
      '#max' => 5,
    ];

    $form['button'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit')
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

    if(mb_strlen($form_state->getValue('name')) < 1 || mb_strlen($form_state->getValue('name')) > 100) {
      $form_state->setErrorByName('name', $this->t('Please enter a real name'));
    }

    if(strlen($form_state->getValue('phone')) !== 10 || !ctype_digit($form_state->getValue('phone'))) {
      $form_state->setErrorByName('phone', $this->t('Please enter a valid Contact Number'));
    }

    if ($form_state->getValue('email') == !Drupal::service('email.validator')->isValid($form_state->getValue('email'))) {
      $form_state->setErrorByName('email',
        $this->t('The email address %mail is not valid.', array('%mail' => $form_state->getValue('email'))));
    }

    if($form_state->getValue('average_mark') < 0 || $form_state->getValue('average_mark') > 5) {
      $form_state->setErrorByName('average_mark', $this->t('Please enter a valid average mark'));
    }
  }

  /**
   * @throws Exception
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    # TODO insert form info to database 'students'
    $fields = [
      'name' => $form_state->getValue('name'),
      'age' => $form_state->getValue('age'),
      'email' => $form_state->getValue('email'),
      'phone' => $form_state->getValue('phone'),
      'average_mark' => $form_state->getValue('average_mark'),
    ];
    $id = Drupal::routeMatch()->getParameter('id');

    Drupal::database()
      ->update('students')
      ->fields($fields)
      ->condition('id', $id, '=')
      ->execute();

    $this->messenger()->addStatus($this->t(':name, form was successfully updated to database.', [
      ':name' => $form_state->getValue('name')
    ]));
    $this->redirect('student_registration.view', ['id' => $id])->send();
  }
}
