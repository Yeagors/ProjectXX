<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    protected $table = 'requests';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string'; // Для UUID

    protected $fillable = [
        'id', // Добавьте это поле
        'brand',
        'model',
        'kpp',
        'year',
        'license_plate',
        'phone',
        'last_name',
        'first_name',
        'middle_name',
        'status',
        'amount',
        'data' // Добавьте, если тоже используется
    ];

    public function photos()
    {
        return $this->hasMany(CarPhoto::class, 'request_id');
    }

    protected $casts = [
        'year' => 'integer'
    ];
}
