<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskApiController;

Route::prefix('v1')->group(function () {
    Route::get('/tasks', [TaskApiController::class, 'index']);
    Route::post('/tasks', [TaskApiController::class, 'store']);
    Route::get('/tasks/{id}', [TaskApiController::class, 'show']);
    Route::put('/tasks/{id}', [TaskApiController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskApiController::class, 'destroy']);
    Route::post('/tasks/{id}/complete', [TaskApiController::class, 'complete']);
    Route::post('/tasks/{id}/chatgpt', [TaskApiController::class, 'chatgpt'])->name('tasks.chatgpt');
});
