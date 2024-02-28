<?php

namespace App\Models;

use Database\Factories\StockFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Stock extends Authenticatable
{
    use HasFactory;

    public $timestamps = false;

    protected $keyType = 'uuid';
    protected $casts = ['id' => 'string', 'previous_stock_id' => 'string'];


    public function previous()
    {
        return $this->belongsTo(Stock::class, 'previous_stock_id');
    }

    protected static function newFactory()
    {
        return StockFactory::new();
    }
}
