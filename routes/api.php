<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\PublicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['cors']], function() {
    // routes login and logout
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    // Routes with user authentication middleware for method access
    Route::group(['middleware' => ['auth:sanctum']], function(){
        // route for user profile
        Route::get('profile',[AuthController::class, 'userProfile']);
        // route for Sign off
        Route::post('logout',[AuthController::class, 'logout']);

        Route::resource('publication', PublicationController::class);
    });
});