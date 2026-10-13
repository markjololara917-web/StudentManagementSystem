<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\OrganizersController;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\RegistrationsController;
use App\Http\Controllers\VenuesController;

Route::get('/', DashboardController::class)->name('dashboard');
Route::resource('events', EventsController::class);
Route::resource('organizers', OrganizersController::class);
Route::resource('venues', VenuesController::class);
Route::resource('participants', ParticipantsController::class);
Route::resource('registrations', RegistrationsController::class);
