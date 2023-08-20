<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\EventBooking;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventBookingController extends Controller
{
    public function index(){
        // Require Where between date
        // $event_bookings = Auth::User()->eventBookings()->join('events', 'event_bookings.event_id', '=', 'events.id')
        //     ->select('event_bookings.*', 'events.title', 'events.slug')->get()
        //     ->transform(function ($eventBooking){
        //         $file = Storage::disk('event_images')->files($eventBooking->event_id);
        //         $file ? $eventBooking->src = $file[0] : $eventBooking->src = '0/cZ5V1tlwn8vtCm4y0TmplP3wFuXPVnwlutSy4HVT.jpg';
        //         $eventBooking->monthYear = date('F-Y', strtotime($eventBooking->begins_at));
        //         $eventBooking->day = date('d', strtotime($eventBooking->begins_at));
        //         $eventBooking->calendarId = date('d-F-Y', strtotime($eventBooking->begins_at));
        //         return $eventBooking;
        //     });
        
        $event_bookings = Auth::User()->eventBookings()->join('events', 'event_bookings.event_id', '=', 'events.id')
            ->select('event_bookings.*', 'events.title', 'events.slug')
            ->where('event_bookings.begins_at', '<=', date('Y-m-d H:i:s', strtotime('+1 year')))
            ->get()
            ->transform(function ($eventBooking){
                $file = Storage::disk('event_images')->files($eventBooking->event_id);
                $file ? $eventBooking->src = $file[0] : $eventBooking->src = '0/cZ5V1tlwn8vtCm4y0TmplP3wFuXPVnwlutSy4HVT.jpg';
                $eventBooking->monthYear = date('F-Y', strtotime($eventBooking->begins_at));
                $eventBooking->day = date('d', strtotime($eventBooking->begins_at));
                return $eventBooking;
            });
            // $event_bookings->groupBy('day', preserveKeys: true);
            // dd($event_bookings);
            $event_bookings = $event_bookings->groupBy(['monthYear', function (object $item) {
                return $item['day'];
            }]);
        // dd($event_bookings);
        // return Inertia::render('EventBookings/Index', ['groupedEventBookings' => $event_bookings->keyBy('day')->groupBy('monthYear', preserveKeys: true)]);
        return Inertia::render('EventBookings/Index', ['groupedEventBookings' => $event_bookings]);
    }

    public function create(Event $event){
        $schedules = $event->eventSchedules()->get()->keyBy('day');
        $file = Storage::disk('event_images')->files($event->id);
        $file ? $event->src = $file[0] : $event->src = '0/cZ5V1tlwn8vtCm4y0TmplP3wFuXPVnwlutSy4HVT.jpg';
        
        return Inertia::render('EventBookings/Create',
            ['event' => $event, 'schedules' => $schedules]);
    }

    public function store(Event $event, Request $request){
        // $event_booking = EventBooking::create($request->all());
        $request['event_id'] = $event->id;
        $request['begins_at'] = date('Y-m-d H:i:s', strtotime($request['begins_at']));
        $request['ends_at'] = date('Y-m-d H:i:s', strtotime($request['ends_at']));
        $event_booking = Auth::User()->eventBookings()->create($request->all());
        return to_route('event_bookings.index');
    }

    public function show(EventBooking $event_booking){
        return Inertia::render('EventBookings/Show', ['eventBooking' => $event_booking]);
    }

    public function update(Request $request){
        if(Auth::User()->id !== $request->user_id) return abort(401, "You do not have permissions to ammend this event.");
        $event_booking = EventBooking::where('id', $request->id)->update($request->toArray());
        return Inertia::render('EventBookings/Show', ['eventBooking' => $event_booking]);
    }

    public function destroy(EventBooking $event_booking){
        $event_booking->delete();
        // return to_route('event_bookings.index');
    }
}
