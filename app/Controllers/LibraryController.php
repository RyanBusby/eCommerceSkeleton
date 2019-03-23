<?php

namespace Cart\Controllers;

use Slim\Router;
use Cart\Models\User;
use Cart\Models\Product;
use Cart\Models\Order;
use Cart\Auth\Auth;
use Cart\Models\OrdersProducts;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LibraryController
{
  public function index(Request $request, Response $response, Twig $view, Product $product, Auth $auth, Order $order, OrdersProducts $ordersproducts, User $user)
  {
    // use joins or something to make less queries
    // select p.title,
    //        p.file
    // from orders_products as op
    // left join orders as o
    // on op.order_id = o.id
    // left join users as u
    // on o.user_id = u.id
    // left join products as p
    // on op.product_id = p.id
    // where u.email = 'ryan@test.com'
    // and o.paid = 1;
    $useremail = $auth->user()['email'];
    $userid = $user->where('email', $useremail)->value('id');
    $orders = $order->where('user_id', $userid)->where('paid', 1)->get()->toArray();
    $orderids = [];
    foreach ($orders as $order) {
      $orderids[] = $order['id'];
    }
    $products = $ordersproducts->whereIn('order_id', $orderids)->get()->toArray();
    $productids = [];
    foreach ($products as $prod) {
      $productids[] = $prod['product_id'];
    }
    $downloads = $product->whereIn('id', $productids)->get()->toArray();
    return $view->render($response, 'library/home.twig', [
      'downloads' => $downloads
    ]);
  }
}
