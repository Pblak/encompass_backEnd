<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LessonInstancesController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TransactionController;
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

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// route for login
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);

Route::middleware( 'auth.multiGuard:student,teacher,parent,admin' )->group(function () {
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);

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
    Route::post('/updateParent', [ParentController::class, 'updateParent']);
    Route::post('/createParent', [ParentController::class, 'createParent']);
    Route::post('/deleteParent', [ParentController::class, 'deleteParent']);

    Route::get('/getTeachers', [TeacherController::class, 'getTeachers']);
    Route::get('/getTeacher/{id}', [TeacherController::class, 'getTeacher']);
    Route::post('/updateTeacher', [TeacherController::class, 'updateTeacher']);
    Route::post('/createTeacher', [TeacherController::class, 'createTeacher']);

    Route::post('/getStudents', [StudentController::class, 'getStudents']);
    Route::get('/getStudent/{id}', [StudentController::class, 'getStudent']);
    Route::post('/updateStudent', [StudentController::class, 'updateStudent']);
    Route::post('/createStudent', [StudentController::class, 'createStudent']);
    Route::post('/deleteStudent', [StudentController::class, 'deleteStudent']);

    // instruments
    Route::get('/getInstruments', [InstrumentController::class, 'getInstruments']);
    Route::get('/getInstrument/{id}', [InstrumentController::class, 'getInstrument']);
    Route::post('/updateInstrument', [InstrumentController::class, 'updateInstrument']);
    Route::post('/createInstrument', [InstrumentController::class, 'createInstrument']);
    Route::delete('/deleteInstrument/{id}', [InstrumentController::class, 'deleteInstrument']);

    // packages
    Route::get('/getPackages', [PackageController::class, 'getPackages']);
    Route::get('/getPackage/{id}', [PackageController::class, 'getPackage']);
    Route::post('/updatePackage', [PackageController::class, 'updatePackage']);
    Route::post('/createPackage', [PackageController::class, 'createPackage']);
    Route::delete('/deletePackage/{id}', [PackageController::class, 'deletePackage']);

    // lessons
    Route::post('/getLessons', [LessonController::class, 'getLessons']);
    Route::get('/getLesson/{id}', [LessonController::class, 'getLesson']);
    Route::post('/updateLesson', [LessonController::class, 'updateLesson']);
    Route::post('/createLesson', [LessonController::class, 'createLesson']);
    Route::post('/deleteLesson/', [LessonController::class, 'deleteLesson']);

    // lesson Instances
    Route::post('/updateLessonInstance', [LessonInstancesController::class, 'updateLessonInstance']);
    Route::post('/getTeacherLessonInstances', [LessonInstancesController::class, 'getTeacherLessonInstances']);

    // rooms
    Route::get('/getRooms', [RoomController::class, 'getRooms']);
    Route::get('/getRoom/{id}', [RoomController::class, 'getRoom']);
    Route::post('/updateRoom', [RoomController::class, 'updateRoom']);
    Route::post('/createRoom', [RoomController::class, 'createRoom']);
    Route::delete('/deleteRoom/{id}', [RoomController::class, 'deleteRoom']);

    // transactions
    Route::get('/getTransactions', [TransactionController::class, 'getTransactions']);
    Route::get('/getTransaction/{id}', [TransactionController::class, 'getTransaction']);
    Route::post('/updateTransaction', [TransactionController::class, 'updateTransaction']);
    Route::post('/createTransaction', [TransactionController::class, 'createTransaction']);
    Route::delete('/deleteTransaction/{id}', [TransactionController::class, 'deleteTransaction']);

    // messages
//    Route::get('/getMessages', [MessageController::class, 'getMessages']);
    Route::post('/searchUserForChat', [MessageController::class, 'searchUserForChat']);
    Route::post('/createMessage', [MessageController::class, 'createMessage']);
    Route::post('/getConversation', [MessageController::class, 'getConversation']);
    Route::post('/getChatUsers', [MessageController::class, 'getChatUsers']);


    // paypal
    Route::post('/createPaypalOrder' , [PaypalController::class , 'createOrder']);
    Route::post('/capturePaypalOrder' , [PaypalController::class , 'capturePayment']);

});
