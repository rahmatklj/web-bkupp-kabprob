<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'commodity_name',
        'unit',
        'price_today',
        'price_yesterday',
        'status',
        'market_location',
        'updated_date',
        'website_url'
    ];

    protected $casts = [
        'updated_date' => 'date',
        'price_today' => 'decimal:2',
        'price_yesterday' => 'decimal:2',
    ];
}
