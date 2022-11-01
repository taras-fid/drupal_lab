<?php

namespace Drupal\mymodule\Form;

use Composer\Package\Package;
use \Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class TestForm extends FormBase
{

  public function getFormId()
  {
    return 'mymodule_testform';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['name'] = [
      '#type' => 'textfield',
      '#description' => 'name text field',
      '#default_value' => ''
    ];

    $form['age'] = [
      '#type' => 'number',
      '#description' => 'age number field',
      '#default_value' => 0
    ];

    $form['email'] = [
      '#type' => 'email',
      '#description' => 'email field',
      '#default_value' => ''
    ];

    $form['button'] = [
      '#type' => 'submit',
      '#description' => 'form submit btn',
      '#value' => 'Submit'
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $form_state_values = $form_state->getValues();
    if (isset($form_state_values['name'])) {
      $redirect_params['name'] = $form_state_values['name'];
    }

    if (isset($form_state_values['age'])) {
      $redirect_params['age'] = $form_state_values['age'];
    }

    if (isset($form_state_values['email'])) {
      $redirect_params['email'] = $form_state_values['email'];
    }

    $url = Url::fromRoute('mymodule.first', $redirect_params);
    $form_state->setRedirectUrl($url);
  }
}