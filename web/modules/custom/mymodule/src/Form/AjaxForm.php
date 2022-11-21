<?php

namespace Drupal\mymodule\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;


class AjaxForm extends FormBase
{
  public function getFormId()
  {
    return 'ajax_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['#prefix'] = '<div id="mymodule-ajax-form-wrapper">';
    $form['#suffix'] = '</div>';

    $step = $form_state->get('step') ?? 1;

    $step_titles = [
      $this->t('Personal data'),
      $this->t('Parameters'),
      $this->t('Survey'),
    ];

    $form['title'] = [
      '#type' => 'item',
      '#title' => $this->t('Step :step from 3: :form_title - :step_title', [
        ':step' => $step,
        ':form_title' => $this->t('Ajax Form'),
        ':step_title' => $step_titles[$step - 1],
      ]),
    ];

    $form['step1']['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#default_value' => $form_state->getValue('name') ?? $form_state->get(['data', 'name']) ?? '',
      '#access' => $step === 1,
    ];
    $form['step1']['surname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Surname'),
      '#default_value' => $form_state->getValue('surname') ?? $form_state->get(['data', 'surname']) ?? '',
      '#access' => $step === 1,
    ];

    $form['step2']['age'] = [
      '#type' => 'number',
      '#min' => 18,
      '#max' => 120,
      '#title' => $this->t('Age'),
      '#default_value' => $form_state->getValue('age') ?? $form_state->get(['data', 'age']) ?? 18,
      '#access' => $step === 2,
    ];
    $form['step2']['gender'] = [
      '#type' => 'radios',
      '#options' => array(
        'male' => $this
          ->t('Male'),
        'female' => $this
          ->t('Female'),
        'other' => $this
          ->t('Other'),
      ),
      '#title' => $this->t('Gender'),
      '#default_value' => $form_state->getValue('gender') ?? $form_state->get(['data', 'gender']),
      '#access' => $step === 2,
    ];

    $form['step3']['interests'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Interests'),
      '#options' => [
        'chess' => $this->t('Chess'),
        'football' => $this->t('Football'),
        'politics' => $this->t('Politics'),
        'gardening' => $this->t('Gardening'),
      ],
      '#default_value' => $form_state->getValue('interests') ?? $form_state->get(['data', 'interests']) ?? [],
      '#access' => $step === 3,
    ];
    $form['step3']['dreams'] = [
      '#type' => 'textarea',
      '#rows' => 10,
      '#title' => $this->t('Dreams'),
      '#default_value' => $form_state->getValue('dreams') ?? $form_state->get(['data', 'dreams']),
      '#access' => $step === 3,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['prev'] = [
      '#type' => 'submit',
      '#value' => $this->t('Prev'),
      '#submit' => ['::prevSubmit'],
      '#access' => $step > 1,
      '#ajax' => [
        'callback' => '::myAjaxCallback',
        'wrapper' => 'mymodule-ajax-form-wrapper',
      ],
    ];
    $form['actions']['next'] = [
      '#type' => 'submit',
      '#value' => $this->t('Next'),
      '#submit' => ['::nextSubmit'],
      '#access' => $step < 3,
      '#ajax' => [
        'callback' => '::myAjaxCallback',
        'wrapper' => 'mymodule-ajax-form-wrapper',
      ],
    ];
    $form['actions']['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#access' => $step === 3,
      '#ajax' => [
        'callback' => '::myAjaxCallback',
        'wrapper' => 'mymodule-ajax-form-wrapper',
      ],
    ];

    $form['#tree'] = TRUE;
    return $form;
  }

  public function myAjaxCallback(array &$form, FormStateInterface $form_state) {
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state)
  {
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $values = $form_state->getValues();
    $data = $form_state->get('data') ?? [];
    $data = array_merge($data, $values['step3']);

    foreach ($data as $key => $value) {
      $this->messenger()->addStatus($this->t(':key => :value', [
        ':key' => $key,
        ':value' => is_array($value) ? implode(', ', array_filter($value)) : $value,
      ]));
    }
  }

  public function nextSubmit(array &$form, FormStateInterface $form_state) {

    $step = $form_state->get('step') ?? 1;

    $values = $form_state->getValues();
    $data = $form_state->get('data') ?? [];
    $form_state->set('data', array_merge($data, $values['step' . $step]));

    $form_state->set('step', ++$step);
    $form_state->setRebuild();
  }

  public function prevSubmit(array &$form, FormStateInterface $form_state) {

    $step = $form_state->get('step') ?? 1;

    $values = $form_state->getUserInput();
    $data = $form_state->get('data') ?? [];
    $form_state->set('data', array_merge($data, $values['step' . $step]));

    $form_state->setValues($data);

    $form_state->set('step', --$step);
    $form_state->setRebuild();
  }
}
