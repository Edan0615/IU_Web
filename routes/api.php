<?php

use App\Http\Controllers\ChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('api.chat.send');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
