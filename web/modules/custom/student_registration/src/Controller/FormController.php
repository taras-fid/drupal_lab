<?php

namespace Drupal\student_registration\Controller;

class FormController {

  public function content(int $id): array {
    $object = \Drupal::database()
      ->select('students', 's')
      ->condition('s.id', $id, '=')
      ->fields('s', ['name', 'age', 'email', 'phone', 'average_mark'])
      ->execute()
      ->fetchAll()[0];
    if ($object) {
      return [
        '#markup' => "Student {$object->name} with email {$object->email}, phone number {$object->phone} and age {$object->age} added."
      ];
    } else {
      return [ '#markup' => "False url" ];
    }
  }
}
