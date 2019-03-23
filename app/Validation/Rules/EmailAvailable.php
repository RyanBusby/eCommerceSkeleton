<?php

namespace Cart\Validation\Rules;

use Cart\Models\User;
use Respect\Validation\Rules\AbstractRule;

class EmailAvailable extends AbstractRule
{
  public function validate($input)
  {
    return User::where('email', $input)->count() === 0;
  }
}
