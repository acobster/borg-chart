<?php

namespace Borg;

use PDO;

class DbConnection {
  protected $connection;

  /**
   * Constructor. Uses environment variables to create a reusable conneciton.
   */
  public function __construct() {
    $dsn = sprintf(
      'mysql:dbname=%s;host=%s',
      getenv('DB_NAME'),
      getenv('DB_HOST')
    );

    $this->connection = new PDO(
      $dsn,
      getenv('DB_USER'),
      getenv('DB_PASSWORD')
    );
  }

  /**
   * Query $sql on the Borg database, optionally binding $params
   * @param string $sql the SQL to run
   * @param array $params the params to bind to $sql
   * @return array the results of the query
   */
  public function query(string $sql, array $params = []) {
    $statement = $this->connection->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
  }
}

?>
