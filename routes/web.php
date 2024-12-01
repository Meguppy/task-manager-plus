<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // タスク関連処理
    Route::get( '/tasks', [TasksController::class,'index'])->name('tasks.index');
    Route::get('/my/tasks', [TasksController::class,'myTasks'])->name('tasks.my');
    Route::get('/tasks/create',[TasksController::class,'create'])->name('tasks.create');
    Route::post('/tasks', [TasksController::class,'store'])->name('tasks.store');
    Route::get('/tasks/{id}/edit',[TasksController::class,'edit'])->name('tasks.edit');
    Route::put('/tasks/done', [TasksController::class,'done'])->name('tasks.done');
    Route::put('/tasks/{id}/undone', [TasksController::class,'undone'])->name('tasks.undone');
    Route::put('/tasks/{id}',[TasksController::class,'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TasksController::class,'destroy'])->name('tasks.destroy');
});

require __DIR__.'/auth.php';
