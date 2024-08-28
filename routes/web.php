<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TaskController;
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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', function () {
    return view('task.index');
});

Route::get('tasks', [TaskController::class, 'index']);
Route::post('tasks', [TaskController::class, 'store']);
Route::get('fetch-tasks', [TaskController::class, 'fetchtask']);
Route::get('edit-tasks/{id}', [TaskController::class, 'edit']);
Route::put('update-tasks/{id}', [TaskController::class, 'update']);
Route::delete('delete-tasks/{id}', [TaskController::class, 'destroy']);
Route::delete('checked-tasks/{id}', [TaskController::class, 'checkedupdate']);
