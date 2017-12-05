<?php

use Borg\Employee;

class EmployeeTest extends PHPUnit\Framework\TestCase {
  public function testAddDistances() {

    $employees = [
      ['id' => '1', 'bossId' => '1', 'name' => 'Bob'],
      ['id' => '6', 'bossId' => '5', 'name' => 'Blob'],
      ['id' => '2', 'bossId' => '1', 'name' => 'Billy'],
      ['id' => '3', 'bossId' => '2', 'name' => 'Billie'],
      ['id' => '4', 'bossId' => '2', 'name' => 'Bobbi'],
      ['id' => '5', 'bossId' => '3', 'name' => 'Bobo'],
    ];

    $expected = [
      ['id' => '1', 'bossId' => '1', 'distance' => 0, 'name' => 'Bob'],
      ['id' => '6', 'bossId' => '5', 'distance' => 4, 'name' => 'Blob'],
      ['id' => '2', 'bossId' => '1', 'distance' => 1, 'name' => 'Billy'],
      ['id' => '3', 'bossId' => '2', 'distance' => 2, 'name' => 'Billie'],
      ['id' => '4', 'bossId' => '2', 'distance' => 2, 'name' => 'Bobbi'],
      ['id' => '5', 'bossId' => '3', 'distance' => 3, 'name' => 'Bobo'],
    ];

    $this->assertEquals($expected, Employee::computeEmployeeDistances($employees));
  }
}

?>
