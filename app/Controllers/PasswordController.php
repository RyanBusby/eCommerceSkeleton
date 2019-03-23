<?php

namespace Cart\Controllers;


use Slim\Views\Twig;
use Slim\Router;
use Cart\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;

class PasswordController
{
  public function getChangePassword($request, $response)
  {
    return $this->view->render($response, 'auth/password/change.twig');
  }
  public function postChangePassword($request, $response)
  {
    $validation = $this->validator->validate($request, [
      'password_old' => v::noWhiteSpace()->notEmpty()->matchesPassword($this->auth->user()->password),
      'password' => v::noWhiteSpace()->notEmpty()
    ]);
    if ($validation->failed()) {
      return $response->withRedirect($this->router->pathFor('auth.password.change'));
    }

    $this->auth->user()->setPassword($request->getParam('password'));
    $this->flash->addMessage('info', 'Password changed');
    return $response->withRedirect($this->router->pathFor('home'));
  }
}
