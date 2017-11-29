<?php

namespace Borg;

class Controller {
  protected $viewDir;

  public function __construct($viewDir) {
    $this->viewDir = $viewDir;
  }

  public function homeAction(array $params) {
    $whitelist = ['s']; // this provides a simple way to support more params
    $whitelistedParams = array_intersect_key($params, array_flip($whitelist));

    $employees = Employee::getAll($whitelistedParams);
    return $this->render('home.php', ['employees' => $employees]);
  }

  protected function render(string $template, array $v) {
    ob_start();
    require realpath("{$this->viewDir}/{$template}");
    return ob_get_clean();
  }
}
