<?php

namespace Database\Factories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'value' => $this->faker->randomDigit(),
            'symbol' => strtoupper(Str::random(3)),
            'date' => $this->faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
            'previous_stock_id' => null
        ];
    }

    public function withPrevious()
    {
        return $this->state(function (array $attributes) {
           return [
               'previous_stock_id' => $attributes['previous']->id ?? null
           ];
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    /*public function previous(int $value, \DateTime $date): static
    {
        return $this->state(function (array $attributes) {
            return [
                'previous_stock_id' => Stock::factory(['symbol' => $attributes['symbol']])->create()->id
            ]
        ];
    }*/
}
