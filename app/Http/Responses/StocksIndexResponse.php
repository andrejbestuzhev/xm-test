<?php

namespace App\Http\Responses;

class StocksIndexResponse extends OkResponse
{
    public function __construct(array $items)
    {
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'symbol' => $item['symbol'],
                'date' => $item['date'],
                'value' => $item['value']
            ];
        }
        parent::__construct($data);
    }
}
