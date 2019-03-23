<?php

namespace Cart\Middleware;

use Cart\Auth\Auth;

class GuestMiddleware
{

  // public function __construct($container)
  // {
  //   $this->container = $container;
  // }
  public function __invoke($request, $response, $next)
  {
    if ($this->auth->check()) {
      return $response->withRedirect($this->container->router->pathFor('home'));
    }
    $response = $next($request, $response);
    return $response;
  }
}
