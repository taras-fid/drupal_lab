<?php

namespace Drupal\database_statement_monitoring_test\mysql;

use Drupal\database_statement_monitoring_test\LoggedStatementsTrait;
use Drupal\mysql\Driver\Database\mysql\Connection as BaseConnection;

/**
 * MySQL Connection class that can log executed queries.
 */
class Connection extends BaseConnection {
  use LoggedStatementsTrait;

}
