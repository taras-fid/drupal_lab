<?php

namespace Drupal\database_statement_monitoring_test\pgsql;

use Drupal\database_statement_monitoring_test\LoggedStatementsTrait;
use Drupal\pgsql\Driver\Database\pgsql\Connection as BaseConnection;

/**
 * PostgreSQL Connection class that can log executed queries.
 */
class Connection extends BaseConnection {
  use LoggedStatementsTrait;

}
