<?php

namespace Drupal\student\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\student\StudentInterface;

/**
 * Defines the student entity class.
 *
 * @ContentEntityType(
 *   id = "student",
 *   label = @Translation("Student"),
 *   label_collection = @Translation("Students"),
 *   label_singular = @Translation("student"),
 *   label_plural = @Translation("students"),
 *   label_count = @PluralTranslation(
 *     singular = "@count students",
 *     plural = "@count students",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\student\StudentListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\student\StudentAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\student\Form\StudentForm",
 *       "edit" = "Drupal\student\Form\StudentForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "student",
 *   admin_permission = "administer student",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "id",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/student",
 *     "add-form" = "/student_entity/add",
 *     "canonical" = "/student_entity/{student}",
 *     "edit-form" = "/student_entity/{student}/edit",
 *     "delete-form" = "/student_entity/{student}/delete",
 *   },
 * )
 */
class Student extends ContentEntityBase implements StudentInterface {
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Contact entity.'))
      ->setReadOnly(TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('name'))
      ->setDescription(t('Student name.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['surname'] = BaseFieldDefinition::create('string')
      ->setLabel(t('surname'))
      ->setDescription(t('Student surname.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -5,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['gender'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Gender'))
      ->setDescription(t('Student gender.'))
      ->setSettings(array(
        'allowed_values' => array(
          'female' => 'female',
          'male' => 'male',
          'other' => 'other',
        ),
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'list_default',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email'))
      ->setDescription(t('Student email.'))
      ->setSettings(array(
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['age'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Age'))
      ->setDescription(t('The student age field'))
      ->setSettings([
        'min' => 18,
        'max' => 120
      ])
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'number', # mb need string
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'number',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['phone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Phone'))
      ->setDescription(t('The student phone field'))
      ->setRequired(TRUE)
      ->setSettings([
        'default_value' => '',
        'max_length' => 9,
      ])
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -3,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'telephone_default',
        'weight' => -3,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['group_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Group ID'))
      ->setDescription(t('The group ID of the student.'))
      ->setSettings([
        'min' => 1
      ])
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'number', # mb need string
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'number',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
