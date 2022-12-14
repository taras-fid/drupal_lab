<?php

namespace Drupal\Tests\hal\Functional\file;

use Drupal\Tests\hal\Functional\EntityResource\HalEntityNormalizationTrait;
use Drupal\Tests\rest\Functional\FileUploadResourceTestBase;

/**
 * Tests binary data file upload route for HAL JSON.
 */
abstract class FileUploadHalJsonTestBase extends FileUploadResourceTestBase {

  use HalEntityNormalizationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['hal'];

  /**
   * {@inheritdoc}
   */
  protected static $format = 'hal_json';

  /**
   * {@inheritdoc}
   */
  protected static $mimeType = 'application/hal+json';

  /**
   * {@inheritdoc}
   */
  protected function getExpectedNormalizedEntity($fid = 1, $expected_filename = 'example.txt', $expected_as_filename = FALSE) {
    $normalization = parent::getExpectedNormalizedEntity($fid, $expected_filename, $expected_as_filename);

    // Cannot use applyHalFieldNormalization() as it uses the $entity property
    // from the test class, which in the case of file upload tests, is the
    // parent entity test entity for the file that's created.

    // The HAL normalization adds entity reference fields to '_links' and
    // '_embedded'.
    unset($normalization['uid']);

    return $normalization + [
      '_links' => [
        'self' => [
          // @todo This can use a proper link once
          // https://www.drupal.org/project/drupal/issues/2907402 is complete.
          // This link matches what is generated from File::url(), a resource
          // URL is currently not available.
          'href' => \Drupal::service('file_url_generator')->generateAbsoluteString($normalization['uri'][0]['value']),
        ],
        'type' => [
          'href' => $this->baseUrl . '/rest/type/file/file',
        ],
        $this->baseUrl . '/rest/relation/file/file/uid' => [
          ['href' => $this->baseUrl . '/user/' . $this->account->id() . '?_format=hal_json'],
        ],
      ],
      '_embedded' => [
        $this->baseUrl . '/rest/relation/file/file/uid' => [
          [
            '_links' => [
              'self' => [
                'href' => $this->baseUrl . '/user/' . $this->account->id() . '?_format=hal_json',
              ],
              'type' => [
                'href' => $this->baseUrl . '/rest/type/user/user',
              ],
            ],
            'uuid' => [
              [
                'value' => $this->account->uuid(),
              ],
            ],
          ],
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   *
   * @see \Drupal\Tests\hal\Functional\EntityResource\EntityTest\EntityTestHalJsonAnonTest::getNormalizedPostEntity()
   */
  protected function getNormalizedPostEntity() {
    return parent::getNormalizedPostEntity() + [
      '_links' => [
        'type' => [
          'href' => $this->baseUrl . '/rest/type/entity_test/entity_test',
        ],
      ],
    ];
  }

}
