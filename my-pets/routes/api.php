<?php


use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\AuthController;
 
Route::post('login', [UserController::class, 'login']);
Route::get('test', [UserController::class, 'test']);
Route::post('register', [UserController::class, 'register']);
Route::post('pets-list', [UserController::class, 'petList']);
Route::post('latest-list', [UserController::class, 'latestpetList']);
Route::post('pets-details', [UserController::class, 'petDetails']);
Route::post('category', [UserController::class, 'category']);
Route::post('add-category', [UserController::class, 'addCategory']);

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('user-details', [UserController::class, 'userDetails']);
    Route::post('add-pets', [UserController::class, 'addPets']);
    
    

});