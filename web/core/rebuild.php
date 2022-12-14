<?php

/**
 * @file
 * Rebuilds all Drupal caches even when Drupal itself does not work.
 *
 * Needs a token query argument which can be calculated using the
 * scripts/rebuild_token_calculator.sh script.
 *
 * @see drupal_rebuild()
 */

use Drupal\Component\Utility\Crypt;
use Drupal\Core\DrupalKernel;
use Drupal\Core\Site\Settings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

// Change the directory to the Drupal root.
chdir('..');

$autoloader = require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/includes/utility.inc';

$request = Request::createFromGlobals();
// Manually resemble early bootstrap of DrupalKernel::boot().
DrupalKernel::bootEnvironment();

try {
  Settings::initialize(dirname(__DIR__), DrupalKernel::findSitePath($request), $autoloader);
}
catch (HttpExceptionInterface $e) {
  $response = new Response('', $e->getStatusCode());
  $response->prepare($request)->send();
  exit;
}

if (Settings::get('rebuild_access', FALSE) ||
  ($request->query->get('token') && $request->query->get('timestamp') &&
    (($request->server->getInt('REQUEST_TIME') - $request->query->get('timestamp')) < 300) &&
    hash_equals(Crypt::hmacBase64($request->query->get('timestamp'), Settings::get('hash_salt')), $request->query->get('token'))
  )) {
  // Clear user cache for all major platforms.
  $user_caches = [
    'apcu_clear_cache',
    'wincache_ucache_clear',
  ];
  array_map('call_user_func', array_filter($user_caches, 'is_callable'));

  drupal_rebuild($autoloader, $request);
  \Drupal::messenger()->addStatus('Cache rebuild complete.');
}
$base_path = dirname($request->getBaseUrl(), 2);
header('Location: ' . $request->getSchemeAndHttpHost() . $base_path);
