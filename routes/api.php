<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;


Route::group(['middleware' => ['cors']], function() {
    Route::get('login', function(){
        return response()->json(['menssaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);;
    })->name('login');

    // routes login and register
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    
    // Routes with user authentication middleware for method access
    Route::group(['middleware' => ['auth:sanctum']], function(){
        // route for user profile
        Route::get('profile',[AuthController::class, 'userProfile']);
        // route for Sign off
        Route::post('logout',[AuthController::class, 'logout']);

        Route::resource('publication', PublicationController::class);
        Route::get('publication/comments', [PublicationController::class, 'commentsByPublication']);

        Route::resource('events', EventController::class);
    });


});

//
Route::get('/comments', [CommentController::class,'index']);
//
Route::post('/comments', [CommentController::class,'store']);
//
Route::put('/comments/{id}', [CommentController::class,'update']);
//
Route::delete('/comments/{id}', [CommentController::class,'destroy']);
