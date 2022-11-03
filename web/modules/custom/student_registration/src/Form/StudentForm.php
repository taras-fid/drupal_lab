<?php

namespace Drupal\student_registration\Form;

use \Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class StudentForm extends FormBase
{

  public function getFormId()
  {
    return 'student_registration_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['name'] = [
      '#type' => 'textfield',
      '#description' => $this->t('name text field'),
      '#required' => TRUE,
      '#default_value' => ''
    ];

    $form['age'] = [
      '#type' => 'number',
      '#description' => $this->t('age number field'),
      '#required' => TRUE,
      '#min' => 16,
      '#max' => 120,
      '#default_value' => 18

    ];

    $form['email'] = [
      '#type' => 'email',
      '#description' => $this->t('email field'),
      '#required' => TRUE,
      '#default_value' => ''
    ];

    $form['phone'] = [
      '#type' => 'tel',
      '#description' => $this->t('phone field (0961111111)'),
      '#default_value' => '',
      '#required' => TRUE,
      '#attributes' => []
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

    if ($form_state->getValue('email') == !\Drupal::service('email.validator')->isValid($form_state->getValue('email'))) {
      $form_state->setErrorByName('email',
        $this->t('The email address %mail is not valid.', array('%mail' => $form_state->getValue('email'))));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $this->messenger()->addStatus($this->t(':name, form was successfully submitted.', [
      ':name' => $form_state->getValue('name')
    ]));
  }
}
