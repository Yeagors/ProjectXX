<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarPhoto extends Model
{
    protected $fillable = ['request_id', 'path'];

    public function request()
    {
        return $this->belongsTo(Requests::class, 'request_id');
    }
    public function auction()
    {
        return $this->belongsTo(Auction::class, 'request_id', 'request_id');
    }
}
