<?php

namespace Borg;

use PDO;

class DbConnection {
  protected $connection;

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

  public function query(string $sql, array $params = []) {
    $statement = $this->connection->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
  }
}

?>
