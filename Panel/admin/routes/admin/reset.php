<?php

use App\Http\Controllers\Admin\Reset\ResetGetController;
use App\Http\Controllers\Admin\Reset\ResetPostController;
use Illuminate\Support\Facades\Route;

Route::get('admin/reset', [ResetGetController::class,'viewReset'])->name('admin.reset');
Route::post('admin/reset/sendCode', [ResetPostController::class,'sendcode']);
Route::post('admin/reset/verifyCode', [ResetPostController::class,'verifyCode']);
Route::post('admin/reset/chnagePass', [ResetPostController::class,'chnagePass']);

