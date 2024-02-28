<?php

namespace App\Http\Responses;

abstract class Response
{
    public string $message = '';
    public int $code = 200;

    public function __construct(string $message = '')
    {
        $this->message = $message;
    }

    public final function result()
    {
        return \Illuminate\Support\Facades\Response::json($this, $this->code);
    }
}
