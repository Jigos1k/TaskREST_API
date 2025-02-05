<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{OrganizationController, CreateController};

Route::get('/organizations/building/{buildingId}', [OrganizationController::class, 'getOrganizationsByBuilding']);
Route::get('/organizations/activity/{activityId}', [OrganizationController::class, 'getOrganizationsByActivity']);
Route::get('/organizations/nearby', [OrganizationController::class, 'getOrganizationsInRadius']);
Route::get('/organizations/{organizationId}', [OrganizationController::class, 'getOrganizationById']);
Route::get('/organizations/search/activity/{activityId}', [OrganizationController::class, 'searchOrganizationsByActivity']);
Route::get('/organizations/search', [OrganizationController::class, 'searchOrganizationsByName']);

Route::get('/table/create', [CreateController::class, 'create']);
