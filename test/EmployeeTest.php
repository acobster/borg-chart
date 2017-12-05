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
      '1' => 0,
      '6' => 4,
      '2' => 1,
      '3' => 2,
      '4' => 2,
      '5' => 3,
    ];

    $this->assertEquals($expected, Employee::computeEmployeeDistances($employees));
  }
}

?>
