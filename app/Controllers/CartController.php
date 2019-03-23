<?php

namespace Cart\Controllers;

use Slim\Views\Twig;
use Cart\Models\Product;
use Cart\Basket\Basket;
use Slim\Router;
use Cart\Basket\Exceptions\QuantityExceededException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CartController
{
  protected $basket;
  protected $product;

  public function __construct(Basket $basket, Product $product)
  {
    $this->basket = $basket;
    $this->product = $product;
  }
  public function index(Request $request, Response $response, Twig $view)
  {
    $this->basket->refresh();
    return $view->render($response, 'cart/index.twig');
  }

  public function add($slug, Request $request, Response $response, Router $router)
  {
    $product = $this->product->where('slug', $slug)->first();
    if (!$product) {
      return $response->withRedirect($router->pathFor('home'));
    }
    $this->basket->add($product);
    return $response->withRedirect($router->pathFor('cart.index'));
  }

  public function update($slug, Request $request, Response $response, Router $router)
  {
    $product = $this->product->where('slug', $slug)->first();

    if (!$product) {
      return $response->withRedirect($router->pathFor('home'));
    }
    return $response->withRedirect($router->pathFor('cart.index'));
  }
  public function clear(Request $request, Response $response, Router $router, Basket $basket)
  {
    $basket->clear();
    return $response->withRedirect($router->pathFor('cart.index'));
  }
}
