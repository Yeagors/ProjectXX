<?php

namespace App\Models;

use Cassandra\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requests extends Model {

    //Включаем мягкое удаление
    use SoftDeletes;
    protected $dates = ['deleted_at']; //Поле для отметок о мягком удалении

    //Задаём параметры таблицы в БД
    protected $table = 'buyer_requests'; //Имя таблицы с которой работает модель
    protected $primaryKey = 'id'; //Первичный ключ таблицы
    public $incrementing  = false; //Отметка является ли ключ AutoIncrement

    public function addRequest($request) {
        // Проверяем наличие всех необходимых полей
        if(!isset($request) || !is_object($request)) {
            return false;
        }

        $requiredFields = ['brand', 'model', 'kpp', 'year', 'license_plate', 'user_phone', 'user_number'];
        foreach ($requiredFields as $field) {
            if (!isset($request->$field)) {
                return false;
            }
        }

        // Генерируем уникальный идентификатор
        $uuid = self::getUuid($request->brand . $request->model . $request->kpp . $request->year . $request->license_plate);

        // Проверяем существование записи и создаем/обновляем при необходимости
        if(!self::where('uuid', $uuid)->exists()) {
            return self::updateOrCreate(
                ['uuid' => $uuid],
                [
                    'uuid' => $uuid,
                    'brand' => $request->brand,
                    'model' => $request->model,
                    'kpp' => $request->kpp,
                    'year' => $request->year,
                    'license_plate' => $request->license_plate,
                    'user_phone' => $request->user_phone,
                    'user_number' => $request->user_number,
                ]
            );
        }
        return false;
    }

    public static function getUuid($key)
    {
        return Uuid::generate(5, $key, Uuid::generate(5, config('app.key'), config('app.uuid')))->string;
    }
}
