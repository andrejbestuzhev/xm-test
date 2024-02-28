<?php

namespace Tests\Unit;

use App\Services\DataProviderServices\AlphaVantageService;
use App\Services\StockService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AlphaVantageServiceTest extends TestCase
{
    private AlphaVantageService $dataProviderService;


    public function setUp(): void
    {
        parent::setUp();
        $this->dataProviderService = app()->make(AlphaVantageService::class);

    }

    public function test_response_parsing()
    {
        Http::fake([
            $this->dataProviderService->makeUrl('IBM') => Http::response(
                file_get_contents(__DIR__ . '/Responses/ok.response')
            )
        ]);

        $this->assertEquals(2, iterator_count($this->dataProviderService->getData('IBM')));
    }

    public function test_fail_response_parsing()
    {
        Http::fake([
            $this->dataProviderService->makeUrl('IBM') => Http::response(
                file_get_contents(__DIR__ . '/Responses/fail.response')
            ),
            $this->dataProviderService->makeUrl('IBN') => Http::response(
                file_get_contents(__DIR__ . '/Responses/fail2.response')
            )
        ]);
        $this->assertEquals(0, iterator_count($this->dataProviderService->getData('IBM')));
        $items = iterator_to_array($this->dataProviderService->getData('IBN'));
        $this->assertTrue($items[0] instanceof \Exception);
    }
}
