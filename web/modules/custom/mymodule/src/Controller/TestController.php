<?php

namespace Drupal\mymodule\Controller;

class TestController {

  public function content(string $name, int $age, string $email) {
    return [
      '#markup' => "User {$name} with email {$email} and age {$age} added."
    ];
  }
}
