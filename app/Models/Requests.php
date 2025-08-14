<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid; // Используем библиотеку ramsey/uuid вместо Cassandra

class Requests extends Model
{
    protected $table = 'buyer_requests';
    public $incrementing = false;
    protected $fillable = [
        'req_id',
        'brand',
        'model',
        'kpp',
        'year',
        'license_plate',
        'user_phone',
        'user_number'
    ];

    public function addRequest($request)
    {
        if(!isset($request->brand, $request->model, $request->kpp, $request->year, $request->license_plate, $request->user_phone)) {
            return false;
        }

        $uuid = self::getUuid($request->brand . $request->model . $request->kpp . $request->year . $request->license_plate);

        if(!self::where('req_id', $uuid)->exists()) {
            return self::updateOrCreate(
                ['req_id' => $uuid],
                [
                    'req_id' => $uuid,
                    'brand' => (string)$request->brand,
                    'model' => (string)$request->model,
                    'kpp' => (string)$request->kpp,
                    'year' => (string)$request->year,
                    'number' => (string)$request->license_plate,
                    'user_phone' => (string)$request->user_phone,
                    'user_name' => (string)$request->user_name ?? null,
                ]
            );
        }

        return false;
    }

    public static function getUuid($key)
    {
        // Генерируем UUID v5 используя пространство имен и имя
        $namespace = Uuid::uuid5(Uuid::NAMESPACE_DNS, config('app.url'));
        return Uuid::uuid5($namespace, $key)->toString();
    }
}
