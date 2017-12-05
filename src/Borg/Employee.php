<?php

namespace Borg;

class Employee {
  /**
   * Fetch all employees from the database
   * @param array $filters an array with any of the following keys:
   * * s - search param, used to filter employees by name
   * @return array and array of Employee objects
   */
  public static function getAll(array $filters) {
    $search = (string) ($filters['s'] ?? '');

    // WHERE depends on whether we have a search param
    $whereClause = $search ? 'WHERE name LIKE :search' : '';
    $sql = "SELECT * FROM employees {$whereClause}";

    // Normally you'd get this from some kind service/container...
    // instantiating stuff directly means ~*! coupling !*~
    $connection = new DbConnection();
    $results = $connection->query($sql, [':search' => "%{$search}%"]);

    return $results;
  }

  public static function getAllWithDistances(array $filters) {
    // get the distance for each employee
    return static::computeEmployeeDistances($results);
  }

  /**
   * Enrich each employee array in $employees with that employee's
   * distance from the CEO
   * TIME COMPLEXITY: O(nd) where d is the average distance from the CEO.
   * In practice, it's probably less than that, since there's a good chance
   * we've already computed the distance for someone's boss, making each
   * of those lookups O(1) instead of O(d).
   * @param array $employees an array of employee arrays
   * @return array
   */
  public static function computeEmployeeDistances(array $employees) {
    // first get each employee keyed by id for O(d) ancestry lookup
    $employees = array_reduce($employees, function(array $keyed, array $employee) {
      // Make the next step a little simpler by recording the CEO's distance
      // assume anyone who is their own boss is the only such person,
      // and that they are the CEO
      if ($employee['id'] === $employee['bossId']) {
        $employee['distance'] = 0;
      }

      $keyed[$employee['id']] = $employee;

      return $keyed;
    }, []);

    // now, to the task at hand.
    // get each employee's distance from the CEO
    $employees = array_map(function(array $employee) use($employees) {

      while (!isset($employee['distance'])) {
        // since this isn't the CEO, we can assume distance >= 1
        $distanceTraversed = $distanceTraversed ?? 1;
        // keep track of higher-ups, starting at this person's boss
        $higherUp = $higherUp ?? $employees[$employee['bossId']];

        if (isset($higherUp['distance'])) {
          // employee's distance to the CEO is the distance to some higher-up
          // plus that higher-up's distance
          $employee['distance'] = $higherUp['distance'] + $distanceTraversed;
        } else {
          // climb the corporate ladder one rung
          $distanceTraversed++;
          $higherUp = $employees[$higherUp['bossId']];
        }
      }

      return $employee;
    }, $employees);

    return array_values($employees);
  }
}

?>
