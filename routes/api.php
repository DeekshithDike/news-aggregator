<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PreferenceController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/scrap-news', [NewsController::class, 'scrapNews']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/news', [NewsController::class, 'fetchNews']);
    Route::get('/preferences/author/list', [PreferenceController::class, 'listAuthPreferences']);
    Route::get('/preferences/author', [PreferenceController::class, 'fetchConfiguredAuthPreferences']);
    Route::get('/preferences/source', [PreferenceController::class, 'fetchConfiguredSrcPreferences']);
    Route::post('/preferences/author', [PreferenceController::class, 'saveAuthPreferences']);
    Route::post('/preferences/source', [PreferenceController::class, 'saveSrcPreferences']);
});