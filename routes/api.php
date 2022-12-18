<?php

use App\Http\Controllers\CoordinatesController;
use Illuminate\Support\Facades\Route;

Route::get('geocode', [CoordinatesController::class, 'geocodeAction']);
Route::get('gmaps', [CoordinatesController::class, 'gmapsAction']);
Route::get('hmaps', [CoordinatesController::class, 'hmapsAction']);
