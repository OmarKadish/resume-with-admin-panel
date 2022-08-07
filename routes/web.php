<?php

use App\Http\Controllers\admin\EducationController;
use App\Http\Controllers\admin\ExperienceController;
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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

//Admin Side Pages
Route::prefix('admin')->group(function () {
    Route::get('/', [App\Http\Controllers\admin\AdminController::class, 'index']);
    Route::get('index', [App\Http\Controllers\admin\AdminController::class, 'index']);

    Route::prefix('experience')->group(function () {
        Route::get('', [ExperienceController::class, 'index']);
        Route::get('create', [ExperienceController::class, 'create']);
        Route::post('store', [ExperienceController::class, 'store']);
        Route::get('edit/{id}', [ExperienceController::class, 'edit']);
        Route::post('update/{id}', [ExperienceController::class, 'update']);
        Route::delete('delete/{id}', [ExperienceController::class, 'destroy']);
    });
    Route::prefix('education')->group(function () {
        Route::get('', [EducationController::class, 'index']);
        Route::get('create', [EducationController::class, 'create']);
        Route::post('store', [EducationController::class, 'store']);
        Route::get('edit/{id}', [EducationController::class, 'edit']);
        Route::post('update/{id}', [EducationController::class, 'update']);
        Route::delete('delete/{id}', [EducationController::class, 'destroy']);
    });
});

