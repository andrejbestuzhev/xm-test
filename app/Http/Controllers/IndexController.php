<?php

namespace App\Http\Controllers;

use App\Http\Responses\StockResponse;
use App\Http\Responses\StocksIndexResponse;
use App\Services\StockService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IndexController extends BaseController
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
                    $change = $this->stockService->change($stock);
                    if ($change > 0) {
                        $direction = 'up';
                    }
                    if ($change === 0) {
                        $direction = 'none';
                    }
                    if ($change <= 0) {
                        $direction = 'down';
                    }
                    $stock->direction = $direction;
                    $result[] = $stock;
                }
            }
            return view('stocks', ['stocks' => $result]);
        }
    }
}
