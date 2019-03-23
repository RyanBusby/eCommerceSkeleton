<?php

namespace Cart\Controllers;

use Slim\Views\Twig;
use Slim\Router;
use Cart\Auth\Auth;
use Cart\Models\Product;
use Cart\Basket\Basket;
use Cart\Models\User;
use Cart\Models\Order;
use Cart\Handlers\EmptyBasket;
use Cart\Handlers\MarkOrderPaid;
use Cart\Handlers\RecordFailedPayment;
use Cart\Handlers\RecordSuccessfulPayment;
use Cart\Event\OrderWasCreated;
use Cart\Validation\Contracts\ValidatorInterface;
use Slim\Flash\Messages;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Cart\Validation\Forms\OrderForm;
use Braintree_Transaction;

class OrderController
{
  protected $basket;
  protected $router;
  protected $validator;
  protected $messages;
  protected $auth;

  public function __construct(Basket $basket, Router $router, ValidatorInterface $validator, Messages $messages, Auth $auth)
  {
    $this->basket = $basket;
    $this->router = $router;
    $this->validator = $validator;
    $this->messages = $messages;
    $this->auth = $auth;
  }

  public function index(Request $request, Response $response, Twig $view)
  {
    $this->basket->refresh();
    if(!$this->auth->check())  {
      $this->messages->addMessage('error', 'Sign in before purchasing Downloads. Not a user? click Sign up');
      return $response->withRedirect($this->router->pathFor('auth.signin'));
    }
    return $view->render($response, 'order/index.twig');
  }

  public function show($hash, Request $request, Response $response, Twig $view, Order $order)
  {
    $order = $order->with(['products'])->where('hash', $hash)->first();
    if (!$order) {
      return $response->withRedirect($this->router->pathFor('home'));
    }
    return $view->render($response, 'order/show.twig', [
      'order' => $order,
    ]);
  }

  public function create(Request $request, Response $response, User $user)
  {

    $this->basket->refresh();
    if (!$this->basket->subTotal()) {
      // items been removed
      return $response->withRedirect($this->router->pathFor('cart.index'));
    }

    if (!$request->getParam('payment_method_nonce')) {
      return $response->withRedirect($this->router->pathFor('order.index'));
    }

    // $validation = $this->validator->validate($request, OrderForm::rules());
    //
    // if ($validation->failed()) {
    //   return $response->withRedirect($this->router->pathFor('order.index'));
    // }
    $hash = bin2hex(random_bytes(32));
    $user = $user->firstOrCreate([
      'email' => $this->auth->user()['email'],
      'name' => $this->auth->user()['name'],
    ]);

    $order = $user->orders()->create([
      'hash' => $hash,
      'paid' => false,
      'total' => $this->basket->subTotal()
    ]);

    $allItems = $this->basket->all();
    $order->products()->saveMany(
      $allItems
    );
    $result = Braintree_Transaction::sale([
      'amount' => $this->basket->subTotal(),
      'paymentMethodNonce' => $request->getParam('payment_method_nonce'),
      'options' => [
        'submitForSettlement' => true,
      ]
    ]);
    $event = new \Cart\Event\OrderWasCreated($order, $this->basket);
    if (!$result->success)  {
      $event->attach(new \Cart\Handlers\RecordFailedPayment);
      $event->dispatch();
      $this->messages->addMessage('error', 'Payment unsuccessful');
      return $response->withRedirect($this->router->pathFor('order.index'));
    }
    $event->attach([
      new \Cart\Handlers\MarkOrderPaid,
      new \Cart\Handlers\RecordSuccessfulPayment($result->transaction->id),
      new \Cart\Handlers\EmptyBasket,
    ]);
    $event->dispatch();

    return $response->withRedirect($this->router->pathFor('order.show', [
      'hash' => $hash,
    ]));
  }

}
