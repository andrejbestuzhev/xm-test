<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Services\DataProviderServices\AlphaVantageService;
use App\Services\DataProviderServices\DataProviderServiceInterface;
use App\Services\StockService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RefreshSymbolValues extends Command
{

    private DataProviderServiceInterface $dataService;
    private StockService $stockService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'symbol:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reloads symbol values from API';

    public function __construct(DataProviderServiceInterface $dataService, StockService $stockService)
    {
        parent::__construct();
        $this->dataService = $dataService;
        $this->stockService = $stockService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->fetchData();
        $this->cacheData();
    }

    private function fetchData()
    {
        $firstRun = Stock::all()->count() === 0;
        $symbols = $this->stockService->getAllSymbols();
        foreach ($symbols as $symbol) {
            Log::info('Fetching data for ' . $symbol);
            try {
                foreach ($this->dataService->getData($symbol) as $item) {
                    $this->stockService->insertStock(
                        $item->symbol,
                        $item->value,
                        $item->date
                    );
                }
            } catch (\Exception $e) {
                Log::error('Unable to load data for symbol ' . $symbol . ': ' . $e->getMessage());
            }
            break;
        }
        if ($firstRun) {
            $this->stockService->setPrevious();
        }
    }

    private function cacheData()
    {
        $symbols = $this->stockService->getAllSymbols();
        foreach ($symbols as $symbol) {
            $stock = $this->stockService->getLastStock($symbol);
            if ($stock) {
                $this->stockService->cacheStock($stock);
            }
        }
    }
}
