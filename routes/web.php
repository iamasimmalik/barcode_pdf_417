<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDF417Controller;

Route::get('/', [PDF417Controller::class, 'index'])->name('home');
Route::post('/pdf417/generate', [PDF417Controller::class, 'generate'])->name('pdf417.generate');
