<?php

use App\Http\Controllers\Admin\Setup\Profile\ProfileSetupGetController;
use App\Http\Controllers\Admin\Setup\Profile\ProfileSetupPostController;
use Illuminate\Support\Facades\Route;

Route::get('admin/setup/profile', [ProfileSetupGetController::class,'viewProfileSetup'])->name('admin.setup_profile');
Route::post('admin/setup/profile/update', [ProfileSetupPostController::class,'updateSetupProfile']);
