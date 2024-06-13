<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{DashboardController, EmployeeController};
use App\Http\Controllers\Api\EmployeeController as ApiEmployeeController;

Route::get('/dashboard', DashboardController::class);
Route::resource('employee', EmployeeController::class)
    ->except('show');
