<?php

declare(strict_types=1);

use App\Http\Controllers\Product\AddProductController;
use App\Http\Controllers\Product\ListProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));
Route::get('/list', ListProductController::class)->name('product_list');
Route::get('/add/{name}/{price?}', AddProductController::class)->name('product_add');
