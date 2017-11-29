<?php

namespace Borg;

class Employee {
  public static function getAll(array $filters) {
    $search = (string) ($filters['s'] ?? '');

    // WHERE depends on whether we have a search param
    $whereClause = $search ? 'WHERE name LIKE "%:search%"' : '';
    $sql = "SELECT * FROM employees {$whereClause}";

    // Normally you'd get this from some kind service/container...
    // instantiating stuff directly means ~*! coupling !*~
    $connection = new DbConnection();
    $results = $connection->query($sql, [':search' => $search]);

    // TODO enrich as Employee objects

    return $results;
  }
}

?>
