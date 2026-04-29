<?php

namespace App\Exceptions;

class UserException extends \Exception
{
    public $statusCode;

    public function __construct($message, $statusCode = 422)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }
}
