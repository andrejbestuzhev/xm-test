<?php

namespace App\Http\Controllers;

use App\Http\Responses\ErrorResponse;
use App\Http\Responses\StockResponse;
use App\Http\Responses\StocksIndexResponse;
use App\Services\StockService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StocksController extends BaseController
{
    private StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index(string $symbol = null)
    {
        $symbols = $this->stockService->getAllSymbols();
        if (!$symbol) {
            $result = [];
            foreach ($symbols as $symbol) {
                $stock = $this->stockService->getCachedStock($symbol);
                if ($stock) {
                    $result[] = $stock;
                }
            }
            return (new StocksIndexResponse($result))->result();
        }
        if (!in_array($symbol, $symbols)) {
            return (new ErrorResponse('Not found', 404))->result();
        }
        $stock = $this->stockService->getCachedStock($symbol);
        return $stock == null
            ? (new ErrorResponse('Not found', 404))->result()
            : (new StockResponse($stock->toArray()))->result();
    }
}
