<?php

use App\Http\Controllers\Account;
use App\Http\Controllers\Client;
use Illuminate\Support\Facades\Route;

Route::name('client.')->prefix('client')->middleware('fw-only-whitelisted', 'passwordCheck')->group(function () {
    Route::get('/dashboard', [Client\DashboardController::class, 'index'])->name('dashboard');
});

Route::name('client.')->prefix('client')->middleware('fw-only-whitelisted')->group(function () {
    Route::get('chat', [Account\ChatController::class, 'index'])->name('chat.index');
    Route::get('chatbox', [Account\ChatController::class, 'chatbox'])->name('chatbox.index');
    Route::get('chatbox/getContent', [Account\ChatController::class, 'getContent'])->name('chatbox.getContent');
    Route::get('chatbox/updateUnreads', [Account\ChatController::class, 'updateUnreads'])->name('chatbox.updateUnreads');
    Route::get('chatbox/readMessage', [Account\ChatController::class, 'readMessage'])->name('chatbox.readMessage');
    Route::get('chatbox/endGuestChat', [Account\ChatController::class, 'endGuestChat'])->name('chatbox.endGuestChat');
    Route::get('chatbox/transcriptChat', [Account\ChatController::class, 'transcriptChat'])->name('chatbox.transcriptChat');
    Route::get('chatbox/getDetail', [Account\ChatController::class, 'getDetail'])->name('chatbox.getDetail');
    Route::post('chatbox/sendMessage', [Account\ChatController::class, 'sendMessage'])->name('chatbox.sendMessage');
});
