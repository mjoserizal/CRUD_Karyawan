<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{DashboardController, EmployeeController};
use App\Http\Controllers\Api\EmployeeController as ApiEmployeeController;

Route::get('/dashboard', DashboardController::class);
Route::resource('employee', EmployeeController::class)
    ->except('show');
Route::post('/employee/upload', [ApiEmployeeController::class, 'upload'])->name('employee.upload');
