<?php

namespace App\Exceptions;

class CredentialsException extends \Exception
{
    public $statusCode;

    public function __construct($message, $statusCode = 401)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }
}
