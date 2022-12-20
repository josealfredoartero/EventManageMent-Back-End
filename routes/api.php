<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentPublicationController;
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
        // route for Sign off
        Route::post('logout',[AuthController::class, 'logout'])->name('logout');
        // route for user profile
        Route::get('profile',[AuthController::class, 'userProfile']);
        // routes for the methods of the controller of Publications
        Route::resource('publication', PublicationController::class)->only(['store','update','destroy']);
        
        Route::resource('events', EventController::class)->only(['store','update','destroy']);

        Route::resource('publication/comment', CommentPublicationController::class)->only(['store','update','destroy']);
        Route::resource('event/comment', CommentController::class)->only(['store','update','destroy']);

    });
    
    Route::get('publication/comments/{id}', [PublicationController::class, 'commentsByPublication']);

    Route::get('publication', [PublicationController::class, 'index']);
    Route::get('publication/{id}', [PublicationController::class, 'show']);

    Route::get('events', [EventController::class, 'index']);

    Route::get('/comments/{id}', [CommentController::class,'count']);
    Route::get('event/comments/{id}', [CommentController::class,'comments']);
});
