<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
    //Route::post('register', 'register')->name('user.register');
    //Route::post('Login', 'Login')->name('user.Login');
});
Route::post('register', [AuthController::class, 'register'])->name('user.register');
Route::post('Login', [AuthController::class, 'Login'])->name('user.Login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('user.logout');

    Route::prefix('report')
        ->controller(ReportController::class)
        ->group(function () {
            Route::get('/', 'index')
                ->name('report.index')
                ->middleware('can:report.index');


            Route::post('/', 'create')
                ->name('report.create')
                ->middleware('can:report.create');


            Route::Put('/{id}', 'update')
                ->name('report.update')
                ->middleware('can:report.update');

            Route::post('/{id}', 'destroy')
                ->name('report.delete')
                ->middleware('can:report.delete');


        });
});
Route::post('/', [ReportController::class, 'create'])->middleware('can:report.create');
Route::put('/{id}', [ReportController::class, 'update'])->middleware('can:report.update');
