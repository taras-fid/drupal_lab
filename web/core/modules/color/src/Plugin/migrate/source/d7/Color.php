<?php

namespace Drupal\color\Plugin\migrate\source\d7;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ThemeHandler;
use Drupal\Core\State\StateInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Row;
use Drupal\migrate_drupal\Plugin\migrate\source\VariableMultiRow;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Drupal 7 color source from database.
 *
 * For available configuration keys, refer to the parent classes.
 *
 * @see \Drupal\migrate\Plugin\migrate\source\SqlBase
 * @see \Drupal\migrate\Plugin\migrate\source\SourcePluginBase
 *
 * @MigrateSource(
 *   id = "d7_color",
 *   source_module = "color"
 * )
 */
class Color extends VariableMultiRow {

  /**
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandler
   */
  protected $themeHandler;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration, StateInterface $state, EntityTypeManagerInterface $entity_type_manager, ThemeHandler $theme_handler) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration, $state, $entity_type_manager);
    $this->themeHandler = $theme_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration = NULL) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $migration,
      $container->get('state'),
      $container->get('entity_type.manager'),
      $container->get('theme_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Get color data for all themes.
    $query = $this->select('variable', 'v')
      ->fields('v', ['name', 'value'])
      ->condition('name', 'color_%', 'LIKE');
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $themes = $this->themeHandler->listInfo();
    $themes_installed = [];
    /** @var \Drupal\Core\Extension\Extension $theme */
    foreach ($themes as $theme) {
      if ($theme->status) {
        $themes_installed[] = $theme->getName();
      }
    }

    // The name is of the form 'color_theme_variable'.
    $name = explode('_', $row->getSourceProperty('name'));

    // Set theme_installed if this source theme is installed.
    if (in_array($name[1], $themes_installed)) {
      $row->setSourceProperty('theme_installed', TRUE);
    }

    return parent::prepareRow($row);
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return [
      'name' => $this->t('A color variable for a theme.'),
      'value' => $this->t('The value of a color variable.'),
    ];
  }

}
