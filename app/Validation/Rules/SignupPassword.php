<?php

namespace Cart\Validation\Rules;

use Cart\Models\User;
use Respect\Validation\Rules\AbstractRule;

class SignupPassword extends AbstractRule
{
  protected $password;
  public function __construct($password)
  {
    $this->password = $password;
  }

  public function validate($input)
  {
    return password_verify($input, $this->password);
  }
}
