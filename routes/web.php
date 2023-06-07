<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventBookingController;
use App\Http\Controllers\EventScheduleController;
use App\Http\Controllers\EventImageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
Route::post('/events', [EventController::class, 'store']);
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
Route::patch('/events/{event}', [EventController::class, 'update'])->name('events.update');
Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

/** ^tbc.. Planner Dashboard for '/events/{event}/bookings' + '/events/{event}/bookings/{id}' */

Route::post('/event-bookings', [EventBookingController::class, 'store']);
Route::get('/event-bookings/{event_booking}', [EventBookingController::class, 'show'])->name('eventBookings.show');
Route::patch('/event-bookings/{event_booking}', [EventBookingController::class, 'update'])->name('eventBookings.update');
Route::delete('/event-bookings/{event_booking}', [EventBookingController::class, 'destroy'])->name('eventBookings.destroy');

Route::patch('/events/{event}/schedules', [EventScheduleController::class, 'update'])->name('eventSchedules.update');
// Route::delete('/events/{event}/schedules', [EventScheduleController::class, 'destroy'])->name('eventSchedules.destroy');

Route::post('/events/{event}/images', [EventImageController::class, 'store'])->name('eventImages.store');
Route::delete('/events/{event}/images', [EventImageController::class, 'destroy'])->name('eventImages.destroy');
