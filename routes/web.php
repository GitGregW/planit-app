<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventBookingController;
use App\Http\Controllers\EventScheduleController;
use App\Http\Controllers\EventImageController;
use App\Http\Controllers\EventTagController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Event;

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
    $active_events = Event::where('is_active', 1)->limit(5)->latest()->get()->transform(function ($event){
        $file = Storage::disk('event_images')->files($event->id);
        $file ? $event->src = $file[0] : $event->src = Storage::disk('event_images')->files('0');
        return $event;
    });

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'activeEvents' => $active_events,
        'activeEventsCount' => Event::where('is_active', 1)->count(),
    ]);
})->name('home');

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

Route::middleware('user_group:Planner')->group(function (){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event:slug}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::patch('/events/{event:slug}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event:slug}', [EventController::class, 'destroy'])->name('events.destroy');

    Route::get('/events/{event:slug}/schedules/create', [EventScheduleController::class, 'create'])->name('event_schedules.create');
    Route::post('/events/{event:slug}/schedules', [EventScheduleController::class, 'store'])->name('event_schedules.store');
    Route::patch('/events/{event:slug}/schedules', [EventScheduleController::class, 'update'])->name('event_schedules.update');
    Route::delete('/events/{event:slug}/schedules', [EventScheduleController::class, 'destroy'])->name('event_schedules.destroy');

    Route::get('/events/{event:slug}/images/create', [EventImageController::class, 'create'])->name('event_images.create');
    Route::get('/events/{event:slug}/images', [EventImageController::class, 'index'])->name('event_images.index');
    Route::post('/events/{event:slug}/images', [EventImageController::class, 'store'])->name('event_images.store');
    Route::delete('/events/{event:slug}/images/{index}', [EventImageController::class, 'destroy'])->name('event_images.destroy');

    Route::post('/events/{event:slug}/tags', [EventTagController::class, 'store'])->name('event_tags.store');
    Route::delete('/events/{event:slug}/tags', [EventTagController::class, 'destroy'])->name('event_tags.destroy');
});
/** ^tbc.. Planner Dashboard for '/events/{event}/bookings' + '/events/{event}/bookings/{id}' */

Route::middleware('user_group:Attendee')->group(function (){
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard');

    Route::get('/event-bookings', [EventBookingController::class, 'index'])->name('event_bookings.index');
    Route::post('/event-bookings/{event:slug}', [EventBookingController::class, 'store'])->name('event_bookings.store');
    Route::get('/event-bookings/{event:slug}/create', [EventBookingController::class, 'create'])->name('event_bookings.create');
    Route::get('/event-bookings/{event_booking}', [EventBookingController::class, 'show'])->name('event_bookings.show');
    Route::patch('/event-bookings/{event_booking}', [EventBookingController::class, 'update'])->name('event_bookings.update');
    Route::delete('/event-bookings/{event_booking}', [EventBookingController::class, 'destroy'])->name('event_bookings.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');
});