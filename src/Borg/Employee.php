<?php

namespace Borg;

use Predis;

// FIXME one thing I'd fix about this design with more time is
// a fully-fledged DistanceService, so we're not always passing
// around data between static methods. I *do* think static methods
// make sense for the model-level getAll* variety.
class Employee {
  const DISTANCE_CACHE_HANDLE = 'all_distances';

  /**
   * Redis cache client instance
   * @var Predis\Client
   */
  protected static $redis;

  /**
   * Gets all employees as arrays, with `distances` indices set
   * array $filters the filters to apply. This arg is a vestige of
   * server-side search filtering, but I've developed an emotional
   * attachment to it so I'm keeping it here.
   * @return array[]
   */
  public static function getAllWithDistances(array $filters = []) {
    //DataTables gets us search for free, so we can disable it here
    //$handle = 'employees?' . urldecode(http_build_query($filters));
    $handle = 'employees?';

    $redis = static::getRedis();

    $all = $redis->get($handle);

    if (empty($all)) {
      // get the distance for each employee
      $distances = static::fetchDistances();

      // get the filtered employees along with the distance for each
      $all = array_map(function(array $employee) use($distances) {
        $employee['distance'] = $distances[$employee['id']];
        return $employee;
      }, static::fetch($filters));

      // set cache for next time
      $redis->set($handle, serialize($all));
    } else {
      $all = unserialize($all);

      if (!$all) {
        throw new \RuntimeException('Bad employee data retrieved from Redis');
      }
    }

    return $all;
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

          // FIXME if we really wanted, we could optimize this even further, e.g. by storing
          // each $higherUp in a stack and storing their distances as we compute them,
          // to avoid redundant computations!
        }
      }

      return $employee;
    }, $employees);

    // finally, extract just the distance from each employee array
    return array_map(function(array $employee) {
      return $employee['distance'];
    }, $employees);
  }


  /**
   * Fetch all employees from the database
   * @param array $filters an array with any of the following keys:
   * * s - search param, used to filter employees by name
   * @return array and array of Employee objects
   */
  protected static function fetch(array $filters) {
    $search = (string) ($filters['s'] ?? '');

    // WHERE depends on whether we have a search param
    $whereClause = $search ? 'WHERE name LIKE :search' : '';
    $sql = <<<_SQL_
SELECT e.*, boss.name boss_name
FROM employees e
JOIN employees boss ON(e.bossId = boss.id)
{$whereClause}
_SQL_;

    // Normally you'd get this from some kind service/container...
    // instantiating stuff directly means ~*! coupling !*~
    $connection = new DbConnection();
    $results = $connection->query($sql, [':search' => "%{$search}%"]);

    return $results;
  }

  protected static function fetchDistances() {
    $redis = static::getRedis();

    $distances = $redis->get(static::DISTANCE_CACHE_HANDLE);

    if ($distances) {
      $distances = unserialize($distances);

      // FIXME calling code would ordinarily handle this, e.g.
      // with some logging/error reporting
      if (empty($distances)) {
        throw new \RuntimeException('Bad distance data retrieved from Redis');
      }
    } else {
      // cache miss: compute and store for next time
      $distances = static::computeEmployeeDistances(static::fetch([]));
      $redis->set(static::DISTANCE_CACHE_HANDLE, serialize($distances));

      // NOTE: for this to work reliably, we'd have to update/bust the cache
      // whenever an employee's distance changes.
    }

    return $distances;
  }

  protected static function getRedis() {
    // another instance of where we'd outsource to a service instead of
    // instantiating directly. Rock 'n' roll, etc.
    if (!static::$redis) {
      static::$redis = new Predis\Client([
        'host' => getenv('REDIS_HOST'),
        'port' => getenv('REDIS_PORT'),
      ]);
      static::$redis->auth(getenv('REDIS_PASS'));
    }

    return static::$redis;
  }
}

?>
