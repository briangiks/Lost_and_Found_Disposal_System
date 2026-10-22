<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisposalController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// Token-protected — see app/Http/Controllers/CronController.php. Lets a free
// external cron-ping service drive the disposal cycle on hosts with no
// native scheduler (e.g. Render's free tier).
Route::get('/cron/run-disposal-cycle', [CronController::class, 'runDisposalCycle'])->name('cron.disposal-cycle');
Route::get('/cron/run-queue', [CronController::class, 'runQueue'])->name('cron.run-queue');
Route::get('/diagnostics/test-mail', [CronController::class, 'testMail'])->name('diagnostics.test-mail');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
    Route::post('/items/{item}/claim', [ItemController::class, 'markClaimed'])->name('items.claim');
    Route::get('/items/{item}/qr', [QrCodeController::class, 'image'])->name('items.qr');
    Route::get('/items/{item}/qr-card', [QrCodeController::class, 'card'])->name('items.qr-card');

    Route::get('/disposal', [DisposalController::class, 'index'])->name('disposal.index');
    Route::post('/disposal/{batch}/send-notice', [DisposalController::class, 'sendNotice'])->name('disposal.send-notice');
    Route::post('/disposal/{batch}/skip-wait', [DisposalController::class, 'skipWait'])->name('disposal.skip-wait');
    Route::post('/disposal/{batch}/release', [DisposalController::class, 'release'])->name('disposal.release');

    Route::get('/emails', [EmailController::class, 'index'])->name('emails.index');
    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');
});
