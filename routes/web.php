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

// http://laravelscholarship.test/login のルーティング
Route::get('login', 'AuthAppController@index');
Route::post('login', 'AuthAppController@auth');

// ログイン後に使用可能となるため、URLにloginを含ませる。
Route::get('login/setting', 'ScholarshipController@setting');
Route::post('login/setting', 'ScholarshipController@setting');
Route::get('login/show', 'ScholarshipController@index');
Route::post('login/show', 'ScholarshipController@index');
Route::post('login/create', 'ScholarshipController@create');
Route::get('login/del', 'ScholarshipController@delete');
Route::get('login/detail', 'ScholarshipController@detail');
Route::get('login/csv', 'ScholarshipController@csv');
Route::post('login/search', 'ScholarshipController@search');

