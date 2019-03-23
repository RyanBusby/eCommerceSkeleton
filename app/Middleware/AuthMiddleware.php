<?php

namespace Cart\Middleware;

use Slim\Views\Twig;
use Slim\Router;
use Cart\Auth\Auth;
use Slim\Csrf\Guard;

class AuthMiddleware
{
  // protected $auth;
  // protected $router;
  // protected $flash;
  //
  // public function __construct($auth, $router, $next)
  // {
  //   $this->auth = $auth;
  //   $this->router = $router;
  // }
  public function __invoke($request, $response, $next)
  {
    if (!$this->auth->check()) {
      $this->flash->addMessage('error', 'Must be signed in.');
      return $response->withRedirect($this->router->pathFor('auth.signin'));
    }
    $response = $next($request, $response);
    return $response;
  }
}
