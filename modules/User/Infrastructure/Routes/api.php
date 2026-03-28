<?php
// modules/User/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    // Phase 2: Complete Profile
    Route::put('/profile', function() { return response()->json(['message' => 'Profile update placeholder']); })->name('me.profile.update');
    Route::post('/contact-phones', function() { return response()->json(['message' => 'Contact phones add placeholder']); })->name('me.contact_phones.store');
    Route::post('/bank-details', function() { return response()->json(['message' => 'Bank details update placeholder']); })->name('me.bank_details.update');
});
