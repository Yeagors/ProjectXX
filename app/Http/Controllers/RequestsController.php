<?php

namespace App\Http\Controllers;


class RequestsController extends Controller
{
    protected $user;
    protected $permissions;
    protected $request;
    protected $page_data;
    protected $response;

    public function __construct() {
        $this->request = Request();
    }

    public function addRequest() {

        if(isset($this->request)) {
           return (new \App\Models\Requests)->addRequest($this->request);
        }
        return false;
    }

    public function calculate()
    {
        // Получаем данные из запроса
        $brand = $this->request->input('brand');
        $model = $this->request->input('model');
        $kpp = $this->request->input('kpp');
        $year = $this->request->input('year');
        $licensePlate = $this->request->input('license_plate');

        // Здесь должна быть ваша логика расчета стоимости
        // Пока просто возвращаем случайное число для примера
        $estimate = rand(100000, 999999);

        return response()->json([
            'estimate' => $estimate,
            'success' => true
        ]);
    }



}
