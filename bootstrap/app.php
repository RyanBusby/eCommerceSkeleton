<?php

use Cart\App;
use Cart\Auth\Auth;
use Cart\Middleware\ValidationErrorsMiddleware;
use Slim\Views\Twig;
use Slim\Csrf\Guard;
use Slim\Flash\Message;
use Illuminate\Database\Capsule\Manager as Capsule;
use Respect\Validation\Validator as v;

session_start();

require __DIR__ . '/../vendor/autoload.php';

$app = new App;

$container = $app->getContainer();
// $container->set('flash', function () { return new \Slim\Flash\Messages();});
$capsule = new Capsule;

$capsule->addConnection([
  'driver' => 'pgsql',
  'host' => getenv('webstore_host'),
  'database' => getenv('webstore_database'),
  'username' => getenv('webstore_username'),
  'password' => getenv('webstore_password'),
  'charset' => 'utf8',
  'collation' => 'utf8_unicode_ci',
  'prefix' => ''
]);


$capsule->setAsGlobal();
$capsule->bootEloquent();

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId(getenv('BT_merchantId'));
Braintree_Configuration::publicKey(getenv('BT_publicKey'));
Braintree_Configuration::privateKey(getenv('BT_privateKey'));

require __DIR__ . '/../app/routes.php';
v::with('Cart\\Validation\\Rules');
$app->add(new \Cart\Middleware\ValidationErrorsMiddleware($container->get(Twig::class)));
$app->add(new \Cart\Middleware\OldInputMiddleware($container->get(Twig::class)));
// $app->add(new \Cart\Middleware\AuthMiddleware($container->get(Auth::class), $container->get('router'), $container->get(Flash::class)));
$app->add(new \Cart\Middleware\CsrfViewMiddleware($container->get(Twig::class), $container->get(Csrf::class)));
// $app->add(new \Cart\Middleware\GuestMiddleware($container->get(Auth::class)));
