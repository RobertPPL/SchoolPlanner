<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return redirect('/home');
});

Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    Route::patch('/teacher/append', [TeacherController::class, 'append_subject'])->name('teacher.appendsubject');
    Route::patch('/teacher/dettach', [TeacherController::class, 'remove_subject'])->name('teacher.dettachsubject');
    Route::post('/teacher/relations', [TeacherController::class, 'getTeacherAllSubjects'])->name('teacher.getTeacherAllSubjects');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('/teacher', TeacherController::class);
    Route::post('/group/destroy_many', [GroupController::class, 'destroy_many'])->name('group.destroy_many');
    Route::resource('/group', GroupController::class)->only('index', 'store', 'update', 'destroy');
    Route::post('/subject/destroy_many', [SubjectController::class, 'destroy_many'])->name('subject.destroy_many');
    Route::resource('/subject', SubjectController::class)->only('index', 'store', 'update', 'destroy');

    Route::patch('/schedule/attach_group', [ScheduleController::class, 'attachGroupToSchedule'])->name('schedule.attach_group');
    Route::get('/schedule/{date?}', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::resource('/schedule', ScheduleController::class)->only('store', 'update', 'destroy');

    Route::post('/room/destroy_many', [RoomController::class, 'destroy_many'])->name('room.destroy_many');
    Route::resource('/room', RoomController::class);
});
