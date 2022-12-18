<?php

use App\Http\Controllers\CoordinatesController;
use Illuminate\Support\Facades\Route;

Route::get('geocode-action', [CoordinatesController::class, 'geocodeAction']);
Route::get('gmaps-action', [CoordinatesController::class, 'gmapsAction']);
Route::get('hmaps-action', [CoordinatesController::class, 'hmapsAction']);
