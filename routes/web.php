<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ORMController;
use Illuminate\Support\Facades\Route;

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
});

Route::get('/test/hasOne', [ORMController::class, 'hasOne']);
Route::get('/test/hasMany', [ORMController::class, 'hasMany']);
Route::get('/test/belongsTo', [ORMController::class, 'belongsTo']);
Route::get('/test/manyToMany', [ORMController::class, 'manyToMany']);
Route::get('/test/oneToOne_Polymorphic', [ORMController::class, 'oneToOne_Polymorphic']);
Route::get('/test/oneToMany_Polymorphic', [ORMController::class, 'oneToMany_Polymorphic']);
Route::get('/test/manyToMany_Polymorphic', [ORMController::class, 'manyToMany_Polymorphic']);


require __DIR__.'/auth.php';
