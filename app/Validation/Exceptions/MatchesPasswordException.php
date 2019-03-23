<?php

namespace Cart\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class MatchesPasswordException extends ValidationException
{
  public static $defaultTemplates = [
    self::MODE_DEFAULT => [
      self::STANDARD => 'Invalid password'
    ],
  ];
}
