<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Systemsettings\SystemUser\SystemUserApiController;
use App\Http\Controllers\Admin\Systemsettings\SystemUser\SystemUserGetController;
use App\Http\Controllers\Admin\Systemsettings\SystemUser\SystemUserPostController;

Route::get('admin/systemsettings/systemuser', [SystemUserGetController::class,'viewSystemUser']);
Route::get('admin/systemsettings/systemuser/edit/{uuid}', [SystemUserGetController::class,'viewSystemUser']);
Route::post('admin/systemsettings/systemuser/create', [SystemUserPostController::class,'create']);
Route::post('admin/systemsettings/systemuser/list', [SystemUserApiController::class,'list']);
Route::post('admin/systemsettings/systemuser/delete', [SystemUserPostController::class,'delete']);
Route::post('admin/systemsettings/systemuser/updateRow', [SystemUserPostController::class,'updateRow']);
Route::post('admin/systemsettings/systemuser/update', [SystemUserPostController::class,'update']);