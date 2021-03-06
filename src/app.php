<?php
use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();
$routes->add('leap_year', new Routing\Route('/is_leap_year/{year}', [
  'year' => null,
  '_controller' => 'Calendar\Controller\LeapYearController::index',
]));
$routes->add('hello', new Routing\Route('/hello/{name}', [
  'name' => 'World',
  '_controller' => 'Page\Render\Controller\PageRenderController::index',
]));

return $routes;