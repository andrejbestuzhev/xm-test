<?php

namespace App\Services\DataProviderServices;

class DataItem
{
    public string $symbol;
    public string $value;
    public \DateTime $date;

    public function __toString(): string
    {
        return implode(',', [$this->symbol, $this->value, $this->date->format('Y-m-d')]);
    }
}
