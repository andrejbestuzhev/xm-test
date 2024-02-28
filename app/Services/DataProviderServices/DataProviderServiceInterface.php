<?php

namespace App\Services\DataProviderServices;

use GuzzleHttp\Client;

interface DataProviderServiceInterface
{
    public function getData(string $symbol): \Generator;
}
