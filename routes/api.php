<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EsimController;
use App\Http\Controllers\QosController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\Api\RecommendationController;

Route::middleware(['auth.api'])->group(function () {

    Route::prefix('recommendations')->group(function () {
        Route::get('/', [RecommendationController::class, 'index']);
    });

    Route::prefix('esim')->group(function () {
        Route::post('/request-profile', [EsimController::class, 'requestProfile']);
        
        Route::get('/profiles/{subscriber}', [EsimController::class, 'listProfiles'])
            ->whereNumber('subscriber')
            ->middleware('check.subscriber');
        
        Route::put('/profiles/{profileId}/suspend', [EsimController::class, 'suspendProfile'])
            ->whereNumber('profileId');
    });
    
    Route::prefix('subscribers')->group(function () {
        Route::post('/', [SubscriberController::class, 'create']);
        
        Route::put('/{subscriber}/tariff', [SubscriberController::class, 'assignTariff'])
            ->whereNumber('subscriber')
            ->middleware('check.subscriber');
    });
    
    Route::prefix('qos')->group(function () {
        Route::post('/report', [QosController::class, 'report']);
    });
});

