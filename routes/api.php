<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ChatController;

    Route::get('/chat/messages', [ChatController::class, 'getMessages']);
    Route::post('/chat/send', [ChatController::class, 'sendMessage']);