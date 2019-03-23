<?php
use Cart\Middleware\AuthMiddleware;
use Cart\Middleware\GuestMiddleware;

// home controller
$app->get('/', ['Cart\Controllers\HomeController', 'index'])->setName('home');
$app->get('/downloads/{slug}', ['Cart\Controllers\HomeController', 'get'])->setName('download.get');
$app->get('/cart', ['Cart\Controllers\CartController', 'index'])->setName('cart.index');

// things only guests can see
// $app->group('', function() {
  // auth controller
  $app->get('/auth/signup', ['Cart\Controllers\AuthController', 'getSignUp'])->setName('auth.signup');
  $app->post('/auth/signup', ['Cart\Controllers\AuthController', 'postSignUp']);
  $app->get('/auth/signin', ['Cart\Controllers\AuthController', 'getSignIn'])->setName('auth.signin');
  $app->post('/auth/signin', ['Cart\Controllers\AuthController', 'postSignIn']);
// })->add(new GuestMiddleware($container));

// things only users can see
// $app->group('', function() {
  // cart controller
  $app->get('/cart/add/{slug}/{quantity}', ['Cart\Controllers\CartController', 'add'])->setName('cart.add');
  $app->post('/cart/update/{slug}', ['Cart\Controllers\CartController', 'update'])->setName('cart.update');
  $app->get('/cart/clear', ['Cart\Controllers\CartController', 'clear'])->setName('cart.clear');

  // order controller
  $app->get('/order', ['Cart\Controllers\OrderController', 'index'])->setName('order.index');
  $app->get('/order/{hash}', ['Cart\Controllers\OrderController', 'show'])->setName('order.show');
  $app->post('/order', ['Cart\Controllers\OrderController', 'create'])->setName('order.create');

  //braintree controller
  $app->get('/braintree/token', ['Cart\Controllers\BraintreeController', 'token'])->setName('braintree.token');

  //auth controller
  $app->get('/auth/signout', ['Cart\Controllers\AuthController', 'getSignOut'])->setName('auth.signout');

  //password controller
  $app->get('/auth/password/change', ['Cart\Controllers\PasswordController', 'getChangePassword'])->setName('auth.password.change');
  $app->post('/auth/password/change', ['Cart\Controllers\PasswordController', 'postChangePassword']);

  //library controller
  $app->get('/library', ['Cart\Controllers\LibraryController', 'index'])->setName('library.index');
// })->add(new AuthMiddleware($container));
