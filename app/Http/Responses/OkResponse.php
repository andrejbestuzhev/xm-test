<?php

namespace App\Http\Responses;

class OkResponse extends Response
{

    public $data;

    public function __construct($data = null)
    {
        $this->data = $data;
        parent::__construct('Ok');

    }
}
