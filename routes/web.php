<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

Route::get('/', [HomeController::class, 'index'])->name('HalamanHome');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('HalamanDashboard');
Route::get('/dashboard/map', [DashboardController::class, 'map'])->name('HalamanMap');
Route::get('/arcgis/login', [DashboardController::class, 'login']);

Route::get('/arcgis/callback', [DashboardController::class, 'callback']);

Route::get('/arcgis/profile', [DashboardController::class, 'profile']);

Route::get('/arcgis/layers', [DashboardController::class, 'layers']);

Route::get('/arcgis/features', [DashboardController::class, 'features'])
    ->name('arcgis.features');

Route::get('/arcgis/test-connection', function () {
    $connection = \App\Models\ArcGISConnection::find(1);

    if (!$connection) {
        return response()->json([
            'status' => false,
            'message' => 'Koneksi belum tersimpan.'
        ], 404);
    }

    try {
        $token = \Illuminate\Support\Facades\Crypt::decryptString(
            $connection->access_token
        );

        return response()->json([
            'status' => true,
            'username' => $connection->username,
            'token_available' => !empty($token),
            'expires_at' => $connection->expires_at,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Gagal membaca token.'
        ], 500);
    }
});

Route::get('/arcgis/features/areal', [DashboardController::class, 'areal']);
Route::get('/arcgis/features/jalan', [DashboardController::class, 'jalan']);
Route::get('/arcgis/features/sungai', [DashboardController::class, 'sungai']);
Route::get(
    '/arcgis/features/survey-points',
    [DashboardController::class, 'surveyPoints']
);


Route::get(
    '/arcgis/features/survey-points/{objectId}/attachments',
    [DashboardController::class, 'surveyAttachments']
);
