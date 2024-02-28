<?php

namespace App\Services\DataProviderServices;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class AlphaVantageService implements DataProviderServiceInterface
{
    private string $url;
    private string $apiKey;

    private Client $client;

    public function __construct(string $url, string $apiKey, Client $client)
    {
        $this->url = $url;
        $this->apiKey = $apiKey;
        $this->client = $client;
    }

    public function makeUrl(string $symbol): string
    {
        $params = [
            'function' => 'TIME_SERIES_DAILY',
            'symbol' => $symbol,
            'outputsize' => 'full',
            'apikey' => $this->apiKey
        ];
        return $this->url . '?' . http_build_query($params);
    }

    public function getData(string $symbol): \Generator
    {
        $url = $this->makeUrl($symbol);
        $result = Http::withHeaders(['content-type' => 'application/json'])->get($url)->json();

        $property = 'Time Series (Daily)';
        if (!isset($result[$property])) {
            yield new \Exception('Bad response');
            return;
        }
        $itemProperty = '4. close';
        foreach ((array)$result[$property] as $key => $item) {
            if (!isset($item[$itemProperty])) {
                continue;
            }
            $dataItem = new DataItem();
            $dataItem->symbol = $symbol;
            $dataItem->value = $item[$itemProperty];
            $dataItem->date = new \DateTime($key);
            yield $dataItem;
        }

    }
}
