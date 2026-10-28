<?php

use App\Http\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitoringHistoryController;
use App\Http\Controllers\QosAnalysisController;
use App\Http\Controllers\QosTestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\SettingsController;
/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');
    
Route::get('/monitoring-qos', [MonitoringController::class, 'index'])
    ->name('monitoring.index');

Route::get('/riwayat-monitoring', [MonitoringHistoryController::class, 'index'])
    ->name('monitoring.history');

Route::get('/analisis-qos', [QosAnalysisController::class, 'index'])
    ->name('qos.analysis');

Route::get('/notifikasi', [NotificationController::class, 'index'])
    ->name('notifications.index');

Route::get('/rekomendasi', [RecommendationController::class, 'index'])
    ->name('recommendations.index');

Route::get('/pengaturan', [SettingsController::class, 'index'])
    ->name('settings.index');

Route::put('/pengaturan', [SettingsController::class, 'update'])
    ->name('settings.update');

Route::post('/dashboard/test-qos/{school}', [
    QosTestController::class,
    'store'
])->name('qos.test');
/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return redirect()->route('dashboard');

});


/*
|--------------------------------------------------------------------------
| Data Sekolah
|--------------------------------------------------------------------------
*/

Route::resource('schools', SchoolController::class)
    ->only([
        'index',
        'create',
        'store' ,
        'edit' ,
        'update' ,
        'destroy' ,
    ]);


/*
|--------------------------------------------------------------------------
| Test QoS
|--------------------------------------------------------------------------
*/

