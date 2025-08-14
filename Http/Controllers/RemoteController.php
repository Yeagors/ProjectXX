<?php

namespace App\Http\Controllers;

class RemoteController extends Controller
{
    protected $user;
    protected $permissions;
    protected $request;
    protected $page_data;
    protected $response;


    public function showFirstPage() {

            $this->page_data['user'] = 'долбоеб';
            dd($this->page_data);
            return view('welcome', $this->page_data);

    }
}
