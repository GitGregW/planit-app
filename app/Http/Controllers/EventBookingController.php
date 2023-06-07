<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\EventBooking;
use Illuminate\Support\Facades\Auth;

class EventBookingController extends Controller
{
    public function store(Request $request){
        if(Auth::User()->userGroup->name !== 'Attendee') return to_route('home');
        $event_booking = EventBooking::create($request->all());
        return to_route('eventBookings.show', [$event_booking]);
    }

    public function show(EventBooking $event_booking){
        if(Auth::User()->userGroup->name !== 'Attendee') return to_route('home');
        return Inertia::render('EventBookings/Show', ['eventBooking' => compact($event_booking)]);
    }

    public function update(Request $request){
        if(Auth::User()->userGroup->name !== 'Attendee') return to_route('home');
        elseif(Auth::User()->id !== $request->user_id) return abort(401, "You do not have permissions to ammend this event.");
        $event_booking = EventBooking::where('id', $request->id)->update($request->toArray());
        return Inertia::render('EventBookings/Show', ['eventBooking' => $event_booking]);
    }

    public function destroy(EventBooking $event_booking){
        if(Auth::User()->userGroup->name !== 'Attendee') return to_route('home');
        $event_booking->delete();
        return Inertia::render('EventBookings/Index');
    }
}
