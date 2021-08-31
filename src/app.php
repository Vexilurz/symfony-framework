<?php
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

class LeapYearController
{
  private function is_leap_year($year = null) {
    if (!is_numeric($year)) {
      return 0;
    }

    if (null === $year) {
      $year = date('Y');
    }

    return 0 === $year % 400 || (0 === $year % 4 && 0 !== $year % 100);
  }

  // public function index(Response $response)    - this didn't work for me... =(
  // https://symfony.com/doc/current/create_framework/http_kernel_controller_resolver.html
  public function index($year)
  {
    if ($this->is_leap_year($year)) {
      return new Response('Yep, this is a leap year!');
    }

    return new Response('Nope, this is not a leap year.');
  }
}

$routes = new Routing\RouteCollection();
$routes->add('hello', new Routing\Route('/hello/{name}', [
  'name' => 'World',
  '_controller' => function ($request) {
    return render_template($request);
  }
]));
$routes->add('leap_year', new Routing\Route('/is_leap_year/{year}', [
  'year' => null,
  '_controller' => 'LeapYearController::index'
]));

return $routes;