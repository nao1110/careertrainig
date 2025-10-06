<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PersonaController;

// トップページ
Route::get('/', function () {
    return view('welcome');
});

// ログインページ
Route::get('/login', function () {
    return view('welcome');
})->name('login');

// 面談の流れ・フィードバックガイド
Route::get('/guide/interview-flow', function () {
    return view('guide.interview-flow');
})->name('guide.interview-flow');

// Google OAuth認証ルート
Route::prefix('auth/google')->group(function () {
    Route::get('/', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.auth');
    Route::get('/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('google.callback');
});

// デモログイン（開発用）
Route::post('/demo-login', [GoogleAuthController::class, 'demoLogin'])->name('demo.login');

// ログアウト
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

// ダッシュボード（認証必須）
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// 認証が必要なルート
Route::middleware(['auth'])->group(function () {
    
    // プロフィール管理
    Route::get('/setup-profile', [ProfileController::class, 'setup'])->name('setup.profile');
    Route::post('/setup-profile', [ProfileController::class, 'store']);
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // アポイントメント管理
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    Route::post('/appointments/{appointment}/approve', [AppointmentController::class, 'approve'])->name('appointments.approve');
    Route::post('/appointments/{appointment}/reject', [AppointmentController::class, 'reject'])->name('appointments.reject');
    Route::post('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
    
    // フィードバック管理
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/appointments/{appointment}/feedback/create', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/appointments/{appointment}/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback/{feedback}', [FeedbackController::class, 'show'])->name('feedback.show');
    
    // ペルソナ管理（コンサルタント向け）
    Route::get('/personas', [PersonaController::class, 'index'])->name('personas.index');
    Route::get('/personas/{persona}', [PersonaController::class, 'show'])->name('personas.show');
    
    // ペルソナサンプル（受験者・コンサルタント共通）
    Route::get('/personas/samples', [PersonaController::class, 'samples'])->name('personas.samples');
});
