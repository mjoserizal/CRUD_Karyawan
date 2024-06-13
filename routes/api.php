<?php

use App\Http\Controllers\Api\EmployeeController as ApiEmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/employees', [ApiEmployeeController::class, 'index']);
Route::prefix('employees')->group(function () {
    Route::get('/', [ApiEmployeeController::class, 'index'])->name('api.employees.index');
    Route::post('/', [ApiEmployeeController::class, 'store'])->name('api.employees.store');
    Route::put('/{employee}', [ApiEmployeeController::class, 'update'])->name('api.employees.update');
    Route::delete('/{id}', [ApiEmployeeController::class, 'destroy'])->name('api.employees.destroy');
    Route::post('/upload', [ApiEmployeeController::class, 'upload'])->name('api.employees.upload');
    Route::get('/{id}', [ApiEmployeeController::class, 'show'])->name('api.employees.show');
});
