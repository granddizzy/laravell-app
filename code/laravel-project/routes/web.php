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

Route::get('/', function () {
    return view('main');
})->name('home');

Route::get('/users', [\App\Http\Controllers\Registration::class, 'index'])->name('users');

Route::get('/register', [\App\Http\Controllers\Registration::class, 'index'])->name('register');

Route::post('/user', [\App\Http\Controllers\Registration::class, 'store'])->name('user.store');
