<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class User extends Model
{
    protected $table = 'users';
    public $incrementing = false;


    /**
     * @var mixed|string
     */


    public static function create($fields): int
    {
        $items = [
            'last_name',
            'first_name',
            'middle_name',
            'bd',
            'user_phone',
            'role',
        ];
        if(self::where('user_phone', $fields->phone)->exists()) return 401;
        $user = new self();
        $salt = '';
        foreach ($items as $item) {
            $user->$item = $fields->$item;
            $salt .= $fields->$item;
        }
        $user->user_id = Requests::getUuid($salt);
        $user->password = password_hash($fields->password, null);
        if($user->save()) return 200;

        return 500;
    }

    public static function validateAuth($request)
    {
        if($user = self::where('user_phone', $request->user_phone)->first()){
            if(password_verify($request->password, $user->password)) return $user;
            return 401;
        }
        return 404;
    }
}
