<?php

namespace Cart\Controllers;

use Slim\Views\Twig;
use Slim\Router;
use Cart\Models\User;
use Cart\Auth\Auth;
use Cart\Validation\Contracts\ValidatorInterface;
use Slim\Flash\Messages;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;

class AuthController
{
  protected $router;
  protected $validator;
  protected $auth;
  protected $messages;

  public function __construct(Router $router, ValidatorInterface $validator, Auth $auth, Messages $messages)
  {
    $this->router = $router;
    $this->validator = $validator;
    $this->auth = $auth;
    $this->messages = $messages;
  }

  public function getSignOut(Request $request, Response $response)
  {
    $this->auth->logout();
    return $response->withRedirect($this->router->pathFor('home'));
  }

  public function getSignIn(Request $request, Response $response, Twig $view)
  {
    return $view->render($response, 'auth/signin.twig');
  }

  public function postSignIn(Request $request, Response $response)
  {
    $auth = $this->auth->attempt(
      $request->getParam('email'),
      $request->getParam('password')
    );
    if (!$auth) {
      $this->messages->addMessage('error', 'Invalid credentials');
      return $response->withRedirect($this->router->pathFor('auth.signin'));
    }

    return $response->withRedirect($this->router->pathFor('cart.index'));

  }

  public function getSignUp(Request $request, Response $response, Twig $view)
  {
    return $view->render($response, 'auth/signup.twig');
  }

  public function postSignUp(Request $request, Response $response, Messages $messages)
  {
    $validation = $this->validator->validate($request, [
      'email' => v::noWhiteSpace()->notEmpty()->email()->EmailAvailable(),
      'name' => v::notEmpty()->alpha(),
      'password' => v::noWhiteSpace()->notEmpty()
    ]);

    if ($validation->failed()) {
      // $this->messages->addMessage('error', 'Email already in use');
      return $response->withRedirect($this->router->pathFor('auth.signup'));
    }

    $user = User::create([
      'email' => $request->getParam('email'),
      'name' => $request->getParam('name'),
      'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
    ]);

    $this->messages->addMessage('info', 'Welcome!');

    $this->auth->attempt($user->email, $request->getParam('password'));

    return $response->withRedirect($this->router->pathFor('cart.index'));
  }
}
