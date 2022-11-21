<?php

namespace Drupal\database_statement_monitoring_test\sqlite;

use Drupal\database_statement_monitoring_test\LoggedStatementsTrait;
use Drupal\sqlite\Driver\Database\sqlite\Connection as BaseConnection;

/**
 * SQlite Connection class that can log executed queries.
 */
class Connection extends BaseConnection {
  use LoggedStatementsTrait;

}
