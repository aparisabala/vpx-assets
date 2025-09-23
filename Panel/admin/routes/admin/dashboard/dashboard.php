<?php

use App\Http\Controllers\Admin\Dashboard\DashboardGetController;
use App\Http\Controllers\Admin\Dashboard\DashboardPostController;
use Illuminate\Support\Facades\Route;

Route::get('admin/dashboard', [DashboardGetController::class,'viewDashboard'])->name('admin.dashboard');
Route::get('admin/dashboard/profile', [DashboardGetController::class,'viewProfileUpdate']);
Route::get('admin/dashboard/password', [DashboardGetController::class,'viewUpdatePasword']);

Route::post('admin/dashboard/updatePassword', [DashboardPostController::class,'updatePassword']);


// Calender
Route::get('admin/dashboard/events', [DashboardGetController::class, 'getEvents']);
Route::get('admin/dashboard/schedule/delete/{id}', [DashboardGetController::class, 'deleteEvent']);
Route::post('admin/dashboard/schedule/{id}', [DashboardPostController::class, 'update']);
Route::post('admin/dashboard/schedule/{id}/resize', [DashboardPostController::class, 'resize']);
Route::get('admin/dashboard/events/search', [DashboardGetController::class, 'search']);

Route::get('admin/dashboard/add-schedule', [DashboardGetController::class, 'viewCalender']);
Route::post('admin/dashboard/create-schedule', [DashboardPostController::class, 'create']);