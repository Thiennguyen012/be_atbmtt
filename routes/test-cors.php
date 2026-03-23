<?php

// Test CORS endpoint
// Add this to routes/api.php temporarily

Route::get('/test-cors', function () {
    return response()->json([
        'message' => 'CORS is working!',
        'origin' => request()->header('Origin'),
        'headers' => request()->headers->all(),
    ]);
});
