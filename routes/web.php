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
Route::get('login', 'LoginController@index');
Route::post('login', 'LoginController@auth');
Route::get('logout', 'LoginController@logout');

// ログイン後に使用可能となるため、URLにloginを含ませる。
Route::get('login/set', 'IndexController@viewSet');
Route::post('login/set', 'IndexController@viewSet');
Route::get('login/show', 'IndexController@viewShow');
Route::post('login/show', 'IndexController@viewShow');
Route::post('login/create', 'SettingController@create');
Route::get('login/ajax_del', 'ScholarshipController@ajaxDelete');
Route::get('login/detail', 'ScholarshipController@detail');
Route::get('login/csv', 'ScholarshipController@csv');
Route::post('login/viewMenu', 'ScholarshipController@viewMenu');
Route::post('login/preset', 'ScholarshipController@viewPreSet');
Route::get('login/ajax_prepay', 'ScholarshipController@ajaxPrePay');

Route::get('login/search', 'ShowController@index');
Route::post('login/search', 'ShowController@index');
