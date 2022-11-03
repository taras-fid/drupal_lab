<?php

namespace Drupal\student_registration\Controller;

class StudentController {

  public function content(string $name, int $age, string $email, string $phone) {
    return [
      '#markup' => "Student {$name} with email {$email}, phone number {$phone} and age {$age} added."
    ];
  }
}
