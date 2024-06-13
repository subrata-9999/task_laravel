<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\DataController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/datapage', [DataController::class, 'index']);
Route::get('/datapagetable', [DataController::class, 'inde']);