<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $table = 'auctions';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'request_id',
        'brand',
        'model',
        'year',
        'kpp',
        'license_plate',
        'mileage',
        'engine',
        'body_condition',
        'engine_condition',
        'transmission_condition',
        'start_price',
        'current_price',
        'current_bid',
        'bid_step',
        'service_fee',
        'inspection_data',
        'status',
        'start_time',
        'end_date'
    ];

    protected $casts = [
        'inspection_data' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function request()
    {
        return $this->belongsTo(Requests::class, 'request_id');
    }

    public function photos()
    {
        return $this->hasMany(CarPhoto::class, 'request_id', 'request_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class)->orderBy('amount', 'desc');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class)->latest();
    }

    public function winningBid()
    {
        return $this->hasOne(Bid::class)->orderBy('amount', 'desc');
    }

    public function getCurrentBidAttribute()
    {
        return $this->bids()->max('amount') ?? $this->start_price;
    }
}
