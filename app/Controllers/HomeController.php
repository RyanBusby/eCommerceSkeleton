<?php

namespace Cart\Controllers;

use Slim\Router;
use Slim\Views\Twig;
use Cart\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{
  public function index(Request $request, Response $response, Twig $view, Product $product)
  {
    $downloads = $product->get();
    return $view->render($response, 'home.twig', [
      'downloads' => $downloads
    ]);
  }

  public function get($slug, Request $request, Response $response, Twig $view, Product $product, Router $router)
  {
    $file = $product->where('slug', $slug)->first();
    if (!$file) {
      return $response->withRedirect($router->pathFor('home'));
    }
    return $view->render($response, 'templates/download.twig', [
      'file' => $file,
    ]);
  }
}
