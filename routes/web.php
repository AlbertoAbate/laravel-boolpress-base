<?php

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
//static page

//home
Route::get('/', 'StaticPageController@home')->name('home');

//about
Route::get('/about', 'StaticPageController@about')->name('about');

// rotte per le resource
Route::resource('posts','PostController');