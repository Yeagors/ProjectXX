<?php

namespace App\Http\Controllers;


use App\Models\User;

class LoginController extends Controller
{
    protected $user;
    protected $permissions;
    protected $request;
    protected $page_data;
    protected $response;

    public function __construct() {
        $this->request = Request();
    }

    public function showLoginForm() {
        return view('login');
    }

    public function auth() {
        return User::validateAuth($this->request);
    }

}
