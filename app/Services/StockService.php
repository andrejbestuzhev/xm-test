<?php

namespace App\Services;

use App\Models\Stock;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class StockService
{
    public function __construct()
    {

    }

    public function insertStock(string $symbol, string $price, \DateTime $date): bool
    {
        $stock = new Stock();
        $stock->id = Str::uuid();
        $stock->value = $price;
        $stock->date = $date;
        $stock->symbol = $symbol;
        $stock->previous_stock_id = null;

        $previousStock = Stock::where('symbol', $symbol)
        ->where('date', '<', $date)
        ->orderBy('date', 'DESC')
        ->first();
        if ($previousStock != null) {
            $stock->previous_stock_id = $previousStock->id;
        }
        return $stock->save();
    }

    // this method is made only because we don't store history for long time
    public function setPrevious(): void
    {
        Stock::orderBy('date', 'asc')->each(function($item) {
            $previousStock = Stock::where('symbol', $item->symbol)
                ->where('date', '<', new \DateTime($item->date))
                ->orderBy('date', 'DESC')
                ->first();
            if ($previousStock != null) {
                $item->previous_stock_id = $previousStock->id;
                $item->save();
            }
        });

    }

    public function getLastStock(string $symbol): Stock|null
    {
        return Stock::where('symbol', $symbol)
            ->orderBy('date', 'DESC')
            ->with('previous')
            ->first();
    }
    public function getAllSymbols(): array
    {
        return config('services.alpha_vantage.symbols');
    }


    public function cacheStock(Stock $stock): bool
    {
        return Cache::set($this->getCacheKey($stock->symbol), $stock);
    }

    public function getCachedStock(string $symbol): Stock|null
    {
        return Cache::get($this->getCacheKey($symbol));
    }

    public function getCacheKey(string $symbol)
    {
        return 'last_' . $symbol;
    }

    public function change(Stock $current): float
    {
        $prev = $current->previous;
        if ($prev == null) {
            return 0;
        }
        if ($prev->value === 0 || $prev->value == $current->value) {
            return 0;
        }

        return 100 * (($current->value - $prev->value) / $prev->value);
    }
}
