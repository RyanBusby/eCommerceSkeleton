<?php

namespace Cart\Models;

use Cart\Models\Payment;
use Cart\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  protected $fillable = [
    'hash',
    'total',
    'paid'
  ];


  public function products()
  {
    return $this->belongsToMany(Product::class, 'orders_products');
  }

  public function payment()
  {
    return $this->hasOne(Payment::class);
  }
}
