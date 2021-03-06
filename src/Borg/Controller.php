<?php

namespace Borg;

class Controller {
  /**
   * @var string directory where view files live
   */
  protected $viewDir;

  /**
   * Constructor
   * @param string $viewDir where to look for view files
   */
  public function __construct(string $viewDir) {
    $this->viewDir = $viewDir;
  }

  /**
   * Render the home page
   * @param array $params the request params
   * @return string the HTML to render
   */
  public function homeAction(array $params) {
    $employees = Employee::getAllWithDistances();
    return $this->render('home.php', ['employees' => $employees]);
  }

  protected function render(string $template, array $v) {
    ob_start();
    require realpath("{$this->viewDir}/{$template}");
    return ob_get_clean();
  }
}
