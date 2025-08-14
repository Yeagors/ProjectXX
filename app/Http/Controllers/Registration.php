<?php

namespace App\Http\Controllers;


use App\Models\User;

class Registration extends Controller
{
    protected $user;
    protected $permissions;
    protected $request;
    protected $page_data;
    protected $response;

    public function __construct() {
        $this->request = Request();
    }

    public function view()
    {
        return view('registration');
    }
}
