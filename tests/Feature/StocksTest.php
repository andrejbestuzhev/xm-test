<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Stock;
use App\Services\DataProviderServices\AlphaVantageService;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StocksTest extends TestCase
{
    use RefreshDatabase;

    private AlphaVantageService $dataProviderService;
    private StockService $stockService;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->dataProviderService = app()->make(AlphaVantageService::class);
        $this->stockService = app()->make(StockService::class);

    }


    public function test_stocks_page(): void
    {
        $this->fillDatabase();
        $response = $this->get('/stocks');
        sleep(1);
        $response->assertStatus(200);
        $this->assertCount(1, $response->json()['data']);
    }

    public function test_stocks_symbol_page(): void
    {
        $this->fillDatabase();
        $response = $this->get('/stocks/IBM');
        sleep(1);
        $response->assertStatus(200);
        $this->assertCount(3, $response->json()['data']);
    }

    public function test_error(): void
    {
        $this->fillDatabase();
        $response = $this->get('/stocks/NOTHING');
        sleep(1);
        $response->assertStatus(404);
    }

    private function fillDatabase()
    {
        Http::fake([
            $this->dataProviderService->makeUrl('IBM') => Http::response(
                file_get_contents(__DIR__ . '/../Unit/Responses/ok.response')
            )
        ]);
        foreach ($this->dataProviderService->getData('IBM') as $item) {
            $this->stockService->insertStock($item->symbol, $item->value, $item->date);
        }
        $this->stockService->cacheStock($this->stockService->getLastStock('IBM'));
    }
}
