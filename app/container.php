<?php

use function DI\get;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Cart\Models\Product;
use Cart\Models\Order;
use Cart\Models\Customer;
use Cart\Models\Payment;
use Cart\Support\Storage\Contracts\StorageInterface;
use Cart\Support\Storage\SessionStorage;
use Cart\Basket\Basket;
use Cart\Validation\Contracts\ValidatorInterface;
use Cart\Validation\Validator;
use Cart\Auth\Auth;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Interop\Container\ContainerInterface;

return [
  'router' => get(Slim\Router::class),

  Messages::class => function (ContainerInterface $c) {
        return new Messages();
  },

  ValidatorInterface::class => function (ContainerInterface $c) {
    return new Validator;
  },

  StorageInterface::class => function (ContainerInterface $c) {
    return new SessionStorage('cart');
  },

  Twig::class => function (ContainerInterface $c) {
    $twig = new Twig(__DIR__ . '/../resources/views', [
      'cache' => false
    ]);
    $twig->addExtension(new TwigExtension(
      $c->get('router'),
      $c->get('request')->getUri()
    ));
    $twig->getEnvironment()->addGlobal('auth', $c->get(Auth::class));
    $twig->getEnvironment()->addGlobal('basket', $c->get(Basket::class));
    $twig->getEnvironment()->addGlobal('messages', $c->get(Messages::class));
    return $twig;
  },

  Product::class => function (ContainerInterface $c) {
    return new Product;
  },

  Order::class => function (ContainerInterface $c) {
    return new Order;
  },

  Customer::class => function (ContainerInterface $c) {
    return new Customer;
  },

  Payment::class => function (ContainerInterface $c) {
    return new Payment;
  },

  Basket::class => function (ContainerInterface $c) {
    return new Basket (
      $c->get(SessionStorage::class),
      $c->get(Product::class)
    );
  },

  Auth::class => function (ContainerInterface $c) {
    return new Auth();
  },

  Csrf::class => function (ContainerInterface $c) {
    return new Guard();
  }

];
