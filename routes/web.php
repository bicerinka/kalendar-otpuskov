<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\FullCalenderController;
use App\Http\Controllers\AuthController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',  [MainController::class,'home'])->name('home');

Route::get('/about',  [MainController::class,'about']);

Route::get('/review',  [MainController::class,'review'])->name('review');

Route::post('/review/check',  [MainController::class,'review_check']);

//Route::get('/user/{id}/{name}', function ($id, $name) {
//    return 'ID: ' . $id . ' Name: ' . $name;
//});





Route::middleware('auth')->group(function () {
    Route::get('/logout',  [AuthController::class,'logout'])->name('logout');
    Route::get('fullcalender', [FullCalenderController::class, 'index']);
    Route::post('fullcalenderAjax', [FullCalenderController::class, 'ajax']);
});

Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class,'showLoginForm'])->name('login');
    Route::post('/login_process',  [AuthController::class,'login'])->name('login_process');
    Route::get('/register',  [AuthController::class,'showRegisterForm'])->name('register');
    Route::post('/register_process',  [AuthController::class,'register'])->name('register_process');
});
