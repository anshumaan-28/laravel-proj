<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

Auth::routes();

// Redirect root URL to login for guests, home for authenticated users
Route::get('/', function () {
    return auth()->check() ? redirect('/home') : redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // Todo Routes
    Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
    Route::patch('/todos/{todo}', [TodoController::class, 'toggle'])->name('todos.toggle');
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');
});
