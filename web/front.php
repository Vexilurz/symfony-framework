<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();

const PAGES = "/../src/pages/";

$map = [
  '/hello' => __DIR__ . PAGES . 'hello.php',
  '/bye'   => __DIR__ . PAGES . 'bye.php',
];

$path = $request->getPathInfo();
if (isset($map[$path])) {
  require $map[$path];
} else {
  $response->setStatusCode(404);
  $response->setContent('Not Found');
}

$response->send();