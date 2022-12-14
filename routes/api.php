<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\CommentController;
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
//
Route::get('/events', [EventController::class,'index']);
//
Route::post('/events', [EventController::class,'store']);
//
Route::put('/events/{id}', [EventController::class,'update']);
//
Route::delete('/events/{id}', [EventController::class,'destroy']);


//
Route::get('/comments', [CommentController::class,'index']);
//
Route::post('/comments', [CommentController::class,'store']);
//
Route::put('/comments/{id}', [CommentController::class,'update']);
//
Route::delete('/comments/{id}', [CommentController::class,'destroy']);
