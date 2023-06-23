<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::post('bot', [\App\Http\Controllers\Controller::class, 'index']);
Route::get('register', [\App\Http\Controllers\Controller::class, 'register']);

/** Bitrix24 Rest API TEST URLs */
// Проверка пользователя в Контактах
Route::get('checkClientInContactList', [\App\Http\Controllers\bitrix\RESTApi::class, 'checkCIDinContactList']);
// Добавление контакта
Route::get('getUser', [\App\Http\Controllers\bitrix\RESTApi::class, 'contactAdd']);

