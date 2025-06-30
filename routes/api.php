<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomController;

// Aplica CORS a todas las rutas API
Route::middleware(['cors'])->group(function () {
    
    Route::controller(HotelController::class)->group(function () {
        Route::get('hotels', 'index');
        Route::post('hotels', 'store');
        Route::put('hotels/{uuid}', 'update');
        Route::get('hotels/one/{uuid}', 'show');
        Route::delete('hotels/{uuid}', 'destroy');
    });

    Route::controller(RoomController::class)->group(function () {
        Route::get('rooms', 'index');
        Route::post('rooms', 'store');
        Route::put('rooms/{uuid}', 'update');
        Route::get('rooms/one/{uuid}', 'show');
        Route::delete('rooms/{uuid}', 'destroy');
        Route::get('rooms/hotel/{hotelUuid}', 'showByHotel');
    });
    
});