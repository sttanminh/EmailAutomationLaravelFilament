<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\PdfDownloadController;
use App\Http\Controllers\ProcessOrderController;
 
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');

Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/customer',[CustomerController::class,'getAll']);


Route::post('/customer',[CustomerController::class,'addCustomer']);


Route::get('/send-pdf',[EmailController::class ,'sendEmails']);



Route::get('/queue-downloads', [PdfDownloadController::class, 'processPdfs']);


Route::get('/process-order',[ProcessOrderController::class,'processOrder']);