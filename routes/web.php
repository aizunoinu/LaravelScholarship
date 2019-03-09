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
Route::post('login/set', 'ScholarshipController@viewSet');
Route::get('login/show', 'ScholarshipController@viewShow');
Route::post('login/show', 'ScholarshipController@viewShow');
Route::post('login/create', 'ScholarshipController@create');
Route::get('login/ajax_del', 'ScholarshipController@ajaxDelete');
Route::get('login/detail', 'ScholarshipController@detail');
Route::get('login/csv', 'ScholarshipController@csv');
Route::get('login/search', 'ScholarshipController@search');
Route::post('login/search', 'ScholarshipController@search');
Route::post('login/viewMenu', 'ScholarshipController@viewMenu');
Route::post('login/preset', 'ScholarshipController@viewPreSet');
Route::get('login/ajax_prepay', 'ScholarshipController@ajaxPrePay');
