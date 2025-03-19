<?php

use Illuminate\Support\Facades\Route;
use Mengdodo\Ngpopen\Http\Controllers\IndexController;

Route::prefix(config('ngp.RoutePrefix'))->group(function () {
    Route::get('getAuth', [IndexController::class, 'getAuth'])->name('ngp.index.getAuth');
    Route::get('code', [IndexController::class, 'code'])->name('ngp.index.code');
    Route::get('token', [IndexController::class, 'access_token'])->name('ngp.index.access_token');
});


