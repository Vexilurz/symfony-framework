<?php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel;

$request = Request::createFromGlobals();
$requestStack = new RequestStack();
$routes = include __DIR__.'/../src/app.php';

$context = new Routing\RequestContext();
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

use Symfony\Component\EventDispatcher\EventDispatcher;

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher, $requestStack));
//$dispatcher->addSubscriber(new Simplex\ContentLengthListener());
//$dispatcher->addSubscriber(new Simplex\GoogleListener());
$listener = new HttpKernel\EventListener\ErrorListener(
  'Calendar\Controller\ErrorController::exception'
);
$dispatcher->addSubscriber($listener);
$dispatcher->addSubscriber(new HttpKernel\EventListener\ResponseListener('UTF-8'));
$dispatcher->addSubscriber(new Simplex\StringResponseListener());

$framework = new Simplex\Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver);
//$framework = new HttpKernel\HttpCache\HttpCache(
//  $framework,
//  new HttpKernel\HttpCache\Store(__DIR__.'/../cache')
//);
$response = $framework->handle($request);

$response->send();