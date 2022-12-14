<?php

namespace Drupal\Tests\quickedit\Kernel;

use Drupal\entity_test\Entity\EntityTest;
use Drupal\filter\Entity\FilterFormat;
use Drupal\quickedit\EditorSelector;
use Drupal\quickedit\MetadataGenerator;
use Drupal\quickedit_test\MockQuickEditEntityFieldAccessCheck;

/**
 * Tests in-place field editing metadata.
 *
 * @group quickedit
 * @group legacy
 */
class MetadataGeneratorTest extends QuickEditTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['quickedit_test'];

  /**
   * The manager for editor plugins.
   *
   * @var \Drupal\Component\Plugin\PluginManagerInterface
   */
  protected $editorManager;

  /**
   * The metadata generator object to be tested.
   *
   * @var \Drupal\quickedit\MetadataGeneratorInterface
   */
  protected $metadataGenerator;

  /**
   * The editor selector object to be used by the metadata generator object.
   *
   * @var \Drupal\quickedit\EditorSelectorInterface
   */
  protected $editorSelector;

  /**
   * The access checker object to be used by the metadata generator object.
   *
   * @var \Drupal\quickedit\Access\QuickEditEntityFieldAccessCheckInterface
   */
  protected $accessChecker;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->editorManager = $this->container->get('plugin.manager.quickedit.editor');
    $this->accessChecker = new MockQuickEditEntityFieldAccessCheck();
    $this->editorSelector = new EditorSelector($this->editorManager, $this->container->get('plugin.manager.field.formatter'));
    $this->metadataGenerator = new MetadataGenerator($this->accessChecker, $this->editorSelector, $this->editorManager);
  }

  /**
   * Tests a simple entity type, with two different simple fields.
   */
  public function testSimpleEntityType() {
    $field_1_name = 'field_text';
    $field_1_label = 'Plain text field';
    $this->createFieldWithStorage(
      $field_1_name, 'string', 1, $field_1_label,
      // Instance settings.
      [],
      // Widget type & settings.
      'string_textfield',
      ['size' => 42],
      // 'default' formatter type & settings.
      'string',
      []
    );
    $field_2_name = 'field_nr';
    $field_2_label = 'Simple number field';
    $this->createFieldWithStorage(
      $field_2_name, 'integer', 1, $field_2_label,
      // Instance settings.
      [],
      // Widget type & settings.
      'number',
      [],
      // 'default' formatter type & settings.
      'number_integer',
      []
    );

    // Create an entity with values for this text field.
    $entity = EntityTest::create();
    $entity->{$field_1_name}->value = 'Test';
    $entity->{$field_2_name}->value = 42;
    $entity->save();
    $entity = EntityTest::load($entity->id());

    // Verify metadata for field 1.
    $items_1 = $entity->get($field_1_name);
    \Drupal::state()->set('quickedit_test_field_access', 'forbidden');
    $this->assertSame(['access' => FALSE], $this->metadataGenerator->generateFieldMetadata($items_1, 'default'));
    \Drupal::state()->set('quickedit_test_field_access', 'neutral');
    $this->assertSame(['access' => FALSE], $this->metadataGenerator->generateFieldMetadata($items_1, 'default'));
    \Drupal::state()->set('quickedit_test_field_access', 'allowed');
    $metadata_1 = $this->metadataGenerator->generateFieldMetadata($items_1, 'default');
    $expected_1 = [
      'access' => TRUE,
      'label' => 'Plain text field',
      'editor' => 'plain_text',
    ];
    $this->assertEquals($expected_1, $metadata_1, 'The correct metadata is generated for the first field.');

    // Verify metadata for field 2.
    $items_2 = $entity->get($field_2_name);
    \Drupal::state()->set('quickedit_test_field_access', 'forbidden');
    $this->assertSame(['access' => FALSE], $this->metadataGenerator->generateFieldMetadata($items_2, 'default'));
    \Drupal::state()->set('quickedit_test_field_access', 'neutral');
    $this->assertSame(['access' => FALSE], $this->metadataGenerator->generateFieldMetadata($items_2, 'default'));
    \Drupal::state()->set('quickedit_test_field_access', 'allowed');
    $metadata_2 = $this->metadataGenerator->generateFieldMetadata($items_2, 'default');
    $expected_2 = [
      'access' => TRUE,
      'label' => 'Simple number field',
      'editor' => 'form',
    ];
    $this->assertEquals($expected_2, $metadata_2, 'The correct metadata is generated for the second field.');
  }

  /**
   * Tests a field whose associated in-place editor generates custom metadata.
   */
  public function testEditorWithCustomMetadata() {
    $this->editorManager = $this->container->get('plugin.manager.quickedit.editor');
    $this->editorSelector = new EditorSelector($this->editorManager, $this->container->get('plugin.manager.field.formatter'));
    $this->metadataGenerator = new MetadataGenerator($this->accessChecker, $this->editorSelector, $this->editorManager);

    $this->editorManager = $this->container->get('plugin.manager.quickedit.editor');
    $this->editorSelector = new EditorSelector($this->editorManager, $this->container->get('plugin.manager.field.formatter'));
    $this->metadataGenerator = new MetadataGenerator($this->accessChecker, $this->editorSelector, $this->editorManager);

    // Create a rich text field.
    $field_name = 'field_rich';
    $field_label = 'Rich text field';
    $this->createFieldWithStorage(
      $field_name, 'text', 1, $field_label,
      // Instance settings.
      [],
      // Widget type & settings.
      'text_textfield',
      ['size' => 42],
      // 'default' formatter type & settings.
      'text_default',
      []
    );

    // Create a text format.
    $full_html_format = FilterFormat::create([
      'format' => 'full_html',
      'name' => 'Full HTML',
      'weight' => 1,
      'filters' => [
        'filter_htmlcorrector' => ['status' => 1],
      ],
    ]);
    $full_html_format->save();

    // Create an entity with values for this rich text field.
    $entity = EntityTest::create();
    $entity->{$field_name}->value = 'Test';
    $entity->{$field_name}->format = 'full_html';
    $entity->save();
    $entity = EntityTest::load($entity->id());

    // Verify metadata.
    $items = $entity->get($field_name);
    \Drupal::state()->set('quickedit_test_field_access', 'forbidden');
    $this->assertSame(['access' => FALSE], $this->metadataGenerator->generateFieldMetadata($items, 'default'));
    \Drupal::state()->set('quickedit_test_field_access', 'neutral');
    $this->assertSame(['access' => FALSE], $this->metadataGenerator->generateFieldMetadata($items, 'default'));
    \Drupal::state()->set('quickedit_test_field_access', 'allowed');
    $metadata = $this->metadataGenerator->generateFieldMetadata($items, 'default');
    $expected = [
      'access' => TRUE,
      'label' => 'Rich text field',
      'editor' => 'wysiwyg',
      'custom' => [
        'format' => 'full_html',
      ],
    ];
    $this->assertEquals($expected, $metadata, 'The correct metadata (including custom metadata) is generated.');
  }

}
