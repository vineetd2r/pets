<?php


use App\Http\Controllers\Api\UserController;

 
Route::post('login', [UserController::class, 'login']);
Route::get('test', [UserController::class, 'test']);
Route::post('register', [UserController::class, 'register']);
 
Route::group(['middleware' => 'auth:api'], function(){
 Route::post('user-details', [UserController::class, 'userDetails']);
});