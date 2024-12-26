<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// Route::get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('/tasks', [TasksController::class, 'indexApi']);
Route::get('/tasks/done', [TasksController::class, 'indexDoneApi']);
Route::get('/tasks/undone', [TasksController::class, 'indexUnDoneApi']);
Route::put('/tasks/{task}/undone', [TasksController::class,'undoneApi']);
Route::put('/tasks/{task}/done', [TasksController::class,'doneApi']);
Route::delete('/tasks/{task}', [TasksController::class,'destroyApi']);


