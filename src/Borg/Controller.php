<?php

namespace Borg;

class Controller {
  protected $viewDir;

  public function __construct($viewDir) {
    $this->viewDir = $viewDir;
  }

  public function homeAction() {
    return $this->render('home.php');
  }

  public function listAction(array $request) {
    header('content-type: application/json');
    return json_encode([
      'foo' => 'bar',
    ]);
  }

  protected function render($template) {
    ob_start();
    require realpath("{$this->viewDir}/{$template}");
    return ob_get_clean();
  }
}
