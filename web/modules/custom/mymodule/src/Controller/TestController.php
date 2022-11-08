<?php

namespace Drupal\mymodule\Controller;

use Drupal\Core\StringTranslation\StringTranslationTrait;

class TestController {
  use StringTranslationTrait;
  public function content(string $name, int $age, string $email) {
    return [
      '#markup' => $this->t('User :name with email :email and age :age added.', [
        ':name' => $name,
        ':email' => $email,
        ':age' => $age,
      ])
    ];
  }
}
