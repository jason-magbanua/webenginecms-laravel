<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\RankingsController;
use App\Http\Controllers\Public\ProfileController;
use App\Http\Controllers\Public\CastleSiegeController;
use App\Http\Controllers\Public\DonationController;
use App\Http\Controllers\Public\VoteController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\DownloadsController;
use App\Http\Controllers\UserCP\DashboardController;
use App\Http\Controllers\UserCP\AccountController;
use App\Http\Controllers\UserCP\CharacterController;
use Illuminate\Support\Facades\Route;

// --- Public ---
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');

Route::get('/rankings', [RankingsController::class, 'index'])->name('rankings.index');
Route::get('/rankings/level', [RankingsController::class, 'level'])->name('rankings.level');
Route::get('/rankings/resets', [RankingsController::class, 'resets'])->name('rankings.resets');
Route::get('/rankings/grandresets', [RankingsController::class, 'grandResets'])->name('rankings.grandresets');
Route::get('/rankings/killers', [RankingsController::class, 'killers'])->name('rankings.killers');
Route::get('/rankings/guilds', [RankingsController::class, 'guilds'])->name('rankings.guilds');
Route::get('/rankings/online', [RankingsController::class, 'online'])->name('rankings.online');
Route::get('/rankings/votes', [RankingsController::class, 'votes'])->name('rankings.votes');
Route::get('/rankings/gens', [RankingsController::class, 'gens'])->name('rankings.gens');
Route::get('/rankings/master', [RankingsController::class, 'master'])->name('rankings.master');

Route::get('/profile/player/{name}', [ProfileController::class, 'player'])->name('profile.player');
Route::get('/profile/guild/{name}', [ProfileController::class, 'guild'])->name('profile.guild');

Route::get('/castle-siege', [CastleSiegeController::class, 'index'])->name('castle-siege');
Route::get('/donation', [DonationController::class, 'index'])->name('donation');
Route::get('/vote', [VoteController::class, 'index'])->name('vote');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/downloads', [DownloadsController::class, 'index'])->name('downloads');

// --- Auth (guests only) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', [PasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendReset'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// --- UserCP ---
Route::middleware(['auth', 'check.banned'])->prefix('usercp')->name('usercp.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/myaccount', [AccountController::class, 'myAccount'])->name('myaccount');
    Route::get('/mypassword', [AccountController::class, 'myPassword'])->name('mypassword');
    Route::put('/mypassword', [AccountController::class, 'updatePassword']);
    Route::get('/myemail', [AccountController::class, 'myEmail'])->name('myemail');
    Route::put('/myemail', [AccountController::class, 'updateEmail']);

    Route::get('/reset', [CharacterController::class, 'reset'])->name('reset');
    Route::post('/reset', [CharacterController::class, 'doReset']);
    Route::get('/addstats', [CharacterController::class, 'addStats'])->name('addstats');
    Route::post('/addstats', [CharacterController::class, 'doAddStats']);
    Route::get('/clearpk', [CharacterController::class, 'clearPk'])->name('clearpk');
    Route::post('/clearpk', [CharacterController::class, 'doClearPk']);
    Route::get('/unstick', [CharacterController::class, 'unstick'])->name('unstick');
    Route::post('/unstick', [CharacterController::class, 'doUnstick']);
    Route::get('/clearskilltree', [CharacterController::class, 'clearSkillTree'])->name('clearskilltree');
    Route::post('/clearskilltree', [CharacterController::class, 'doClearSkillTree']);

    Route::get('/vote', [VoteController::class, 'claim'])->name('vote');
    Route::post('/vote', [VoteController::class, 'processClaim'])->name('vote.submit');
});
