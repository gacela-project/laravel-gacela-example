<?php

use App\Product\Infrastructure\Controller\AddProductController;
use App\Product\Infrastructure\Controller\ListProductController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', fn () => view('welcome'));
Route::get('/list', ListProductController::class)->name('product_list');
Route::get('/add/{name}', AddProductController::class)->name('product_add');
