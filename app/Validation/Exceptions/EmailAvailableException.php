<?php

namespace Cart\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;
use Cart\Validation\Rules\EmailAvailable;

class EmailAvailableException extends ValidationException
{
  public static $defaultTemplates = [
    self::MODE_DEFAULT => [
      self::STANDARD => 'Email is already in use.'
    ],
  ];
}
