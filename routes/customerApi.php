<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::get('/customer',[CustomerController::class,'getAll']);


Route::post('/customer',[CustomerController::class,'addCustomer']);