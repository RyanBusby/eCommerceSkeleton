<?php

namespace Cart\Validation\Rules;

use Cart\Models\User;
use Respect\Validation\Rules\AbstractRule;

class MatchesPassword extends AbstractRule
{
  protected $password;
  public function __construct($password, $retypedpassword)
  {
    $this->password = $password;
    $this->retypedpassword = $retypedpassword
  }

  public function validate($input)
  {
    return password_verify($input, $this->password);
  }
}
