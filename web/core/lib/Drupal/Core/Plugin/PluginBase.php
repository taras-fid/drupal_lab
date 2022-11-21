<?php

namespace Drupal\Core\Plugin;

use Drupal\Component\Plugin\PluginBase as ComponentPluginBase;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Base class for plugins supporting metadata inspection and translation.
 *
 * @ingroup plugin_api
 */
abstract class PluginBase extends ComponentPluginBase {
  use StringTranslationTrait;
  use DependencySerializationTrait;
  use MessengerTrait;

}
