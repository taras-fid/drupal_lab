<?php

namespace Drupal\student_registration\Controller;

class FormController {

  public function content() {
    $database = \Drupal::database();
    $a = $database->select('students', 's')->condition('s.id', 1, '=')->fields('s', ['name', 'age', 'email', 'phone', 'average_mark'])->execute()->fetchAll()[0];
    return [
      '#markup' => $a
    ];
  }
}
