<?php

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

Route::get('/', function () {
    return view('welcome');
});

// http://laravelscholarship.test/login

// Loginビューのルーティング
Route::get('login', 'Login@index');
Route::post('login', 'Login@index');
Route::get('logout', 'Login@logout');

// Indexビューのルーティング
Route::get('login/set', 'Index@viewSet');
Route::post('login/set', 'Index@viewSet');
Route::get('login/show', 'Index@viewShow');
Route::post('login/show', 'Index@viewShow');

// Settingビューのルーティング
Route::post('login/create', 'Setting@create');

// Showビューのルーティング
Route::get('login/ajax_del', 'Show@ajaxDelete');
Route::get('login/csv', 'Show@csv');
Route::post('login/preset', 'Show@viewPreSet');
Route::get('login/search', 'Show@index');
Route::post('login/search', 'Show@index');
Route::post('login/redirectMenu', 'Show@redirectMenu');

// Presetビューのルーティング
Route::get('login/ajax_prepay', 'Scholarship@ajaxPrePay');

