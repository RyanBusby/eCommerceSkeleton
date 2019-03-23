<?php

namespace Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Cart\Models\Order;

class User extends Model
{
  protected $table = 'users';
  protected $fillable = [
    'email',
    'name',
    'password',
  ];

  public function setPassword($password)
  {
    $this->update([
      'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);
  }

  public function orders()
  {
    return $this->hasMany(Order::class);
  }
}
