<?php

namespace Drupal\student_registration\Controller;

class ListController {

  public function content() {
    $database = \Drupal::database();
    $object = $database->select('students', 's')->fields('s', ['id','name', 'email'])->execute()->fetchAll();
    $list = [];
    if ($object) {
      foreach ($object as $row) {
        $list[] =['#markup' => "<div class='inline' style='float: left; display: inline'>$row->name, $row->email <br /><form action='./registration/{$row->id}/edit'><input type='submit' value='Edit'/></form><br /> <form action='./registration/{$row->id}/delete'><input type='submit' value='Delete'/></form></div><bre>", '#allowed_tags' => ['div','form', 'bre', 'input']];
      }
      return [
        $list
      ];
    }
    else {
      return [ '#markup' => 'No registrations yet' ];
    }
  }
}
