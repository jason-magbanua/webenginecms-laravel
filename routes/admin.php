<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\CharacterController;
use App\Http\Controllers\Admin\BanController;
use App\Http\Controllers\Admin\CreditController;
use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\CronController;
use App\Http\Controllers\Admin\PluginController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // News
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::get('/news/{id}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{id}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{id}', [NewsController::class, 'destroy'])->name('news.destroy');

    // Accounts
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::get('/accounts/online', [AccountController::class, 'online'])->name('accounts.online');
    Route::get('/accounts/registrations', [AccountController::class, 'registrations'])->name('accounts.registrations');
    Route::get('/accounts/ip/{ip}', [AccountController::class, 'byIp'])->name('accounts.by-ip');
    Route::get('/accounts/{id}', [AccountController::class, 'show'])->name('accounts.show');

    // Characters
    Route::get('/characters', [CharacterController::class, 'index'])->name('characters.index');
    Route::get('/characters/{name}/edit', [CharacterController::class, 'edit'])->name('characters.edit');
    Route::put('/characters/{name}', [CharacterController::class, 'update'])->name('characters.update');

    // Bans
    Route::get('/bans', [BanController::class, 'index'])->name('bans.index');
    Route::get('/bans/create', [BanController::class, 'create'])->name('bans.create');
    Route::post('/bans', [BanController::class, 'store'])->name('bans.store');
    Route::delete('/bans/{id}', [BanController::class, 'destroy'])->name('bans.destroy');
    Route::get('/blocked-ips', [BanController::class, 'blockedIps'])->name('bans.blocked-ips');
    Route::post('/blocked-ips', [BanController::class, 'storeBlockedIp'])->name('bans.blocked-ips.store');
    Route::delete('/blocked-ips/{id}', [BanController::class, 'destroyBlockedIp'])->name('bans.blocked-ips.destroy');

    // Credits
    Route::get('/credits', [CreditController::class, 'index'])->name('credits.index');
    Route::get('/credits/paypal', [CreditController::class, 'paypal'])->name('credits.paypal');
    Route::get('/credits/top-voters', [CreditController::class, 'topVoters'])->name('credits.top-voters');
    Route::post('/credits/{account}/add', [CreditController::class, 'add'])->name('credits.add');
    Route::post('/credits/{account}/remove', [CreditController::class, 'remove'])->name('credits.remove');

    // Cache
    Route::get('/cache', [CacheController::class, 'index'])->name('cache.index');
    Route::delete('/cache/{key}', [CacheController::class, 'clear'])->name('cache.clear');
    Route::delete('/cache', [CacheController::class, 'clearAll'])->name('cache.clear-all');

    // Cron
    Route::get('/cron', [CronController::class, 'index'])->name('cron.index');
    Route::post('/cron/{id}/toggle', [CronController::class, 'toggle'])->name('cron.toggle');
    Route::post('/cron/{id}/run', [CronController::class, 'run'])->name('cron.run');

    // Plugins
    Route::get('/plugins', [PluginController::class, 'index'])->name('plugins.index');
    Route::post('/plugins/{id}/toggle', [PluginController::class, 'toggle'])->name('plugins.toggle');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});
