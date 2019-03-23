<?php

namespace Cart\Basket;
use Cart\Support\Storage\Contracts\StorageInterface;
use Cart\Models\Product;
use Cart\Basket\Exceptions\QuantityExceededException;

class Basket
{
  protected $storage;
  protected $product;

  public function __construct(StorageInterface $storage, Product $product)
  {
    $this->storage = $storage;
    $this->product = $product;
  }

  public function add(Product $product)
  {
    $this->update($product);
  }
  public function update(Product $product)
    {
      $this->storage->set($product->id, [
        'product_id' => (int) $product->id
      ]);
    }

  public function remove(Product $product)
  {
    $this->storage->unset($product->id);
  }


  public function has(Product $product)
  {
    return $this->storage->exists($product->id);
  }

  public function get(Product $product)
  {
    return $this->storage->get($product->id);
  }

  public function clear()
  {
    $this->storage->clear();
  }

  public function all()
  {
    $ids = [];
    $items = [];

    foreach ($this->storage->all() as $product) {
      $ids[] = $product['product_id'];
    }
    $products = $this->product->find($ids);

    foreach ($products as $product) {
      $items[] = $product;
    }
    return $items;
  }

  public function itemCount()
  {
    return count($this->storage);
  }

  public function subTotal()
  {
    $total = 0;

    foreach ($this->all() as $item) {

      $total = $total + $item->price;
    }
    return $total;
  }
  public function refresh()
  {
    foreach ($this->all() as $item) {
      $this->update($item);
    }
  }
}
