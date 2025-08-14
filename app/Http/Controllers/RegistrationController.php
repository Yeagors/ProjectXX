<?php

namespace App\Http\Controllers;


use App\Models\User;

class RegistrationController extends Controller
{
    protected $user;
    protected $request;
    protected $response;

    protected $check = [
        'last_name',
        'first_name',
        'middle_name',
        'bd',
        'user_phone',
        'role',
        'password',
    ];

    public function __construct() {
        $this->request = Request();
    }

    public function view()
    {
        return view('registration');
    }

    public function setRegistration() {
        if(isset($this->request)) {
            if($this->validation($this->request)) {
                return User::create($this->request);
            }
        }
        return false;
    }

    protected function validation($request): bool
    {
        foreach ($this->check as $check) {
            if(!isset($request->$check)) return false;
        }
        return true;
    }
}
