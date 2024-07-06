<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\StudentController;
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

//    Route::get('/getNotifications', [NotificationController::class, 'getNotifications']);
//    Route::get('/getNotification/{id}', [NotificationController::class, 'getNotification']);
//    Route::put('/updateNotification/{id}', [NotificationController::class, 'updateNotification']);
//    Route::post('/createNotification', [NotificationController::class, 'createNotification']);
//    Route::delete('/deleteNotification/{id}', [NotificationController::class, 'deleteNotification']);

    Route::get('/getParents', [ParentController::class, 'getParents']);
    Route::get('/getParent/{id}', [ParentController::class, 'getParent']);
    Route::put('/updateParent/{id}', [ParentController::class, 'updateParent']);
    Route::post('/createParent', [ParentController::class, 'createParent']);

    Route::get('/getTeachers', [TeacherController::class, 'getTeachers']);
    Route::get('/getTeacher/{id}', [TeacherController::class, 'getTeacher']);
    Route::post('/updateTeacher', [TeacherController::class, 'updateTeacher']);
    Route::post('/createTeacher', [TeacherController::class, 'createTeacher']);

    Route::get('/getStudents', [StudentController::class, 'getStudents']);
    Route::get('/getStudent/{id}', [StudentController::class, 'getStudent']);
    Route::put('/updateStudent/{id}', [StudentController::class, 'updateStudent']);
    Route::post('/createStudent', [StudentController::class, 'createStudent']);
    Route::delete('/deleteStudent/{id}', [StudentController::class, 'deleteStudent']);

    // instruments
    Route::get('/getInstruments', [InstrumentController::class, 'getInstruments']);
    Route::get('/getInstrument/{id}', [InstrumentController::class, 'getInstrument']);
    Route::post('/updateInstrument', [InstrumentController::class, 'updateInstrument']);
    Route::post('/createInstrument', [InstrumentController::class, 'createInstrument']);
    Route::delete('/deleteInstrument/{id}', [InstrumentController::class, 'deleteInstrument']);



});
