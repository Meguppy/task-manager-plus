<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;

Route::get('/', function () {
    return view('welcome');
});

// タスク関連処理
Route::get('/tasks',[TasksController::class,'index'])->name('tasks.index');
Route::get('/my/tasks', [TasksController::class,'myTasks'])->name('tasks.my');
Route::get('/tasks/create',[TasksController::class,'create'])->name('tasks.create');
Route::post('/tasks', [TasksController::class,'store'])->name('tasks.store');
Route::get('/tasks/{id}/edit',[TasksController::class,'edit'])->name('tasks.edit');
Route::put('/tasks/{id}',[TasksController::class,'update'])->name('tasks.update');
Route::delete('/tasks/{id}', [TasksController::class,'destroy'])->name('tasks.destroy');
Route::put('/tasks/done',[TasksController::class,'done'])->name('tasks.done');
Route::put('/tasks/undone',[TasksController::class,'undone'])->name('tasks.undone');
