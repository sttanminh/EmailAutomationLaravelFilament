<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

Class CustomerController extends Controller{

public function getAll() {
    return response() -> json(Customer::all(),200);
    }

public function addCustomer(Request $request){

    $validate =$request -> validation([
        'name'=> 'required|string|max:200',
        'email'=> 'required|email|unique:Customer',
        'password'=> 'required|min:6'
    ]);

    $customer = Customer::create([
        'name'=> $validate['name'],
        'email'=> $validate['email'],
        'password'=> hash::make($validate['password'])
    ]);

    return response() -> json($customer,201);
}


}
