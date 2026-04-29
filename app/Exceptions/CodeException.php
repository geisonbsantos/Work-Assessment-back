<?php

namespace App\Exceptions;

class CodeException extends \Exception
{
    public $statusCode;

    public function __construct($message, $statusCode = 401)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }
}
