<?php

use Illuminate\Support\Facades\Route;

// Catch-all route for Vue Router history mode
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');

// routes/web.php or api.php
Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['status' => 'Database connected successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Database connection failed: ' . $e->getMessage()]);
    }
});
