<?php

namespace App\Http\Responses;

class StockResponse extends OkResponse
{
    public function __construct(array $item)
    {
        parent::__construct(['symbol' => $item['symbol'], 'date' => $item['date'], 'value' => $item['value']]);
    }
}
