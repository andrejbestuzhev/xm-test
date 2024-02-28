<?php

namespace Tests\Unit;

use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\Feature\StocksTest;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;

class StockServiceTest extends TestCase
{
    use RefreshDatabase;

    private StockService $stocksService;

    public function setUp(): void
    {
        parent::setUp();
        $this->stocksService = app()->make(StockService::class);
    }

    public function test_create_record(): void
    {
        $result = $this->stocksService->insertStock(
            'ABC', 1, new \DateTime('2024-01-01')
        );
        $this->assertTrue($result);
    }

    public function test_create_record_with_parent()
    {
        $result = $this->stocksService->insertStock(
            'ABC', 1, new \DateTime('2024-01-01')
        );
        $this->assertTrue($result);
        $result = $this->stocksService->insertStock(
            'ABC', 3, new \DateTime('2024-01-02')
        );
        $this->assertTrue($result);
    }

    public function test_last_record()
    {
        $this->stocksService->insertStock(
            'ABC', 1, new \DateTime('2024-01-01')
        );
        $this->stocksService->insertStock(
            'ABC', 3, new \DateTime('2024-01-02')
        );
        $this->assertEquals('ABC', $this->stocksService->getLastStock('ABC')->symbol);
    }

    public function test_change()
    {
        $this->stocksService->insertStock(
            'ABC', 1, new \DateTime('2024-01-01')
        );
        $this->stocksService->insertStock(
            'ABC', 1.5, new \DateTime('2024-01-02')
        );
        $last = $this->stocksService->getLastStock('ABC');
        $this->assertEquals(50, $this->stocksService->change($last));
    }

    public function test_cache_key()
    {
        $this->assertEquals('last_TST', $this->stocksService->getCacheKey('TST'));
    }

    public function test_cache()
    {
        $this->stocksService->insertStock(
            'ABC', 1.5, new \DateTime('2024-01-02')
        );
        $this->stocksService->cacheStock($this->stocksService->getLastStock('ABC'));
        $cached = Cache::get($this->stocksService->getCacheKey('ABC'));
        assertEquals(1.5, $cached->value);
    }
}
