<?php

use App\Http\Controllers\Api\GuildMarkController;
use App\Http\Controllers\Api\PaypalController;
use App\Http\Controllers\Api\CronTriggerController;
use App\Http\Controllers\Api\CastleSiegeController;
use Illuminate\Support\Facades\Route;

Route::get('/guild-mark/{data}', [GuildMarkController::class, 'show']);
Route::post('/paypal/ipn', [PaypalController::class, 'ipn']);
Route::get('/cron', [CronTriggerController::class, 'run']);
Route::get('/castle-siege', [CastleSiegeController::class, 'live']);
