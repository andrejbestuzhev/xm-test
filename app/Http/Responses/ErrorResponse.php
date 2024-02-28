<?php

namespace App\Http\Responses;

class ErrorResponse extends Response
{

    public function __construct(string $message = 'Error', int $code = 400)
    {
        $this->message = $message;
        $this->code = $code;

        parent::__construct($message);
    }
}
