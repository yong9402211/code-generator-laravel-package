<?php

use Illuminate\Support\Facades\Route;

$prefix = 'employer-users';

Route::group(['prefix' => $prefix], function () {
    Route::get('', [EmployerUserController::class, 'index']);
    Route::post('', [EmployerUserController::class, 'store']);
    Route::put('{uuid}', [EmployerUserController::class, 'update']);
    Route::get('{uuid}', [EmployerUserController::class, 'show']);
    Route::delete('{uuid}', [EmployerUserController::class, 'delete']);
    Route::post('login', [EmployerUserController::class, 'login']);
    Route::post('register', [EmployerUserController::class, 'register']);
    Route::get('forgot-password', [EmployerUserController::class, 'forgotPassword']);
    Route::post('change-password', [EmployerUserController::class, 'changePassword']);
    Route::post('change-email', [EmployerUserController::class, 'changeEmail']);
});
