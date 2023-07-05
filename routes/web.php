<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndexController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('', [IndexController::class, 'index'])->name('index');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'todo-lists', 'controller' => TodoListController::class], function (){
    Route::get('', 'create')->name('todo-lists.create');
    Route::post('', 'store')->name('todo-lists.store');
    Route::get('{todoList}', 'show')->name('todo-lists.show');

    Route::middleware('auth')->group(function (){
        Route::get('{todoList}/edit', 'edit')->name('todo-lists.edit');
        Route::put('{todoList}', 'update')->name('todo-lists.update');
        Route::delete('{todoList}', 'destroy')->name('todo-lists.destroy');
    });
});
