<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// route for login
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/getEvents', [EventController::class, 'getEvents']);
    Route::get('/getEvent/{id}', [EventController::class, 'getEvent']);
    Route::put('/updateEvent/{id}', [EventController::class, 'updateEvent']);
    Route::post('/createEvent', [EventController::class, 'createEvent']);

    Route::get('/getKids', [App\Http\Controllers\KidController::class, 'getKids']);
    Route::get('/getKid/{id}', [App\Http\Controllers\KidController::class, 'getKid']);
    Route::put('/updateKid/{id}', [App\Http\Controllers\KidController::class, 'updateKid']);
    Route::post('/createKid', [App\Http\Controllers\KidController::class, 'createKid']);


    // teacher routes
    Route::get('/getTeachers', [TeacherController::class, 'getTeachers']);
    Route::get('/getTeacher/{id}', [TeacherController::class, 'getTeacher']);
    Route::put('/updateTeacher/{id}', [TeacherController::class, 'updateTeacher']);
    Route::post('/createTeacher', [TeacherController::class, 'createTeacher']);


});
