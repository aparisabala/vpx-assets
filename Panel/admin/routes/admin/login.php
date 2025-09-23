<?php

use App\Http\Controllers\Admin\Login\LoginGetController;
use App\Http\Controllers\Admin\Login\LoginPostController;
use App\Http\Controllers\Admin\Logout\LogoutGetController;
use Illuminate\Support\Facades\Route;

Route::get('admin/login', [LoginGetController::class,'viewLogin'])->name('admin.login.home');
Route::post('admin/login', [LoginPostController::class,'postAdminLogin']);
Route::get('admin/logout', [LogoutGetController::class,'logout']);