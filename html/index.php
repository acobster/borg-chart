<?php

use Dotenv\Dotenv;

define('APP_ROOT', realpath(__DIR__.'/..'));

require APP_ROOT.'/vendor/autoload.php';

// parse variables from .env
$dotenv = new Dotenv(APP_ROOT);
$dotenv->overload();

// FIXME normally this would be handled by a proper router/dispatcher,
// e.g. Alto or symfony/routing...good enough for rock 'n' roll.
$controller = new Borg\Controller(APP_ROOT.'/views/'); // pretty sweet class name
// remove the query string
$route = preg_replace('/\?.*$/', '', getenv('REQUEST_URI'));
switch ($route) {
  case '/':
    $response = $controller->homeAction($_GET);
    break;
  default:
    $response = 'This page is irrelevant. Assimilate now.';
    break;
}

echo $response;

?>
