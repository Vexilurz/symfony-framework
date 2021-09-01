<?php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing;

use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

$request = Request::createFromGlobals();
$routes = include __DIR__.'/../src/app.php';

$context = new Routing\RequestContext();
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

use Symfony\Component\EventDispatcher\EventDispatcher;

$dispatcher = new EventDispatcher();
$dispatcher->addListener('response', function (Simplex\ResponseEvent $event) {
  $response = $event->getResponse();

  if ($response->isRedirection()
    || ($response->headers->has('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'html'))
    || 'html' !== $event->getRequest()->getRequestFormat()
  ) {
    return;
  }

  $response->setContent($response->getContent().'GA CODE');
});
$dispatcher->addListener('response', function (Simplex\ResponseEvent $event) {
  $response = $event->getResponse();
  $headers = $response->headers;

  if (!$headers->has('Content-Length') && !$headers->has('Transfer-Encoding')) {
    $headers->set('Content-Length', strlen($response->getContent()));
  }
}, -255);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new Simplex\Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver);
$response = $framework->handle($request);

$response->send();