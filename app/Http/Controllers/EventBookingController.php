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
    public function index(Request $request)
    {
        $start_date = date('Y-m-01', strtotime($request->query('startDate')
            ? $request->query('startDate') : 'now'));
        
        $end_date = date('Y-m-t', strtotime($request->query('endDate')
            ? $request->query('endDate') : '+11 months'));

        $event_bookings = Auth::User()->eventBookings()
            ->join('events', 'event_bookings.event_id', '=', 'events.id')
            ->select('event_bookings.*', 'events.title', 'events.slug')
            ->whereBetween('event_bookings.begins_at', [$start_date, $end_date])
            ->get()
            ->transform(function ($event_booking){
                $event_booking->setDates();
                $event_booking->setImage();
                return $event_booking;
            })
            ->groupBy(['monthYear', function (object $item) {
                return $item['day'];
            }]
        );

        return Inertia::render('EventBookings/Index', [
            'monthlyEventBookings' => $event_bookings,
            'startDate' => date('Y-m', strtotime($start_date)),
            'endDate' => date('Y-m', strtotime($end_date))
        ]);
    }

    public function create(Event $event)
    {
        $schedules = $event->eventSchedules()->get()->keyBy('day');
        $file = $event->getImages();
        $event->src = $file ? $file[0] : $event->src = Storage::disk('event_images')->files('0');
        
        $event_bookings = Auth::User()->eventBookings()
            ->join('events', 'event_bookings.event_id', '=', 'events.id')
            ->select('event_bookings.id', 'event_bookings.begins_at', 'events.title')
            ->whereBetween('event_bookings.begins_at', [
                date('Y-m-01 H:i:s', strtotime('now')),
                date('Y-m-t H:i:s', strtotime('+1 year'))
            ])
            ->get()
            ->transform(function ($event_booking){
                $event_booking->setDates();
                return $event_booking;
            })
            ->groupBy(['monthYear', function (object $item) {
                return $item['day'];
            }]
        );

        return Inertia::render('EventBookings/Create',
            ['event' => $event, 'schedules' => $schedules, 'monthlyEventBookings' => $event_bookings]);
    }

    public function store(Event $event, Request $request)
    {
        $request['event_id'] = $event->id;
        $request['begins_at'] = date('Y-m-d H:i:s', strtotime($request['begins_at']));
        $request['ends_at'] = date('Y-m-d H:i:s', strtotime($request['ends_at']));
        $event_booking = Auth::User()->eventBookings()->create($request->all());
        return to_route('event_bookings.index');
    }

    public function show(EventBooking $event_booking)
    {
        return Inertia::render('EventBookings/Show', ['eventBooking' => $event_booking]);
    }

    public function update(Request $request)
    {
        if(Auth::User()->id !== $request->user_id) return abort(401, "You do not have permissions to ammend this event.");
        $event_booking = EventBooking::where('id', $request->id)->update($request->toArray());
        return Inertia::render('EventBookings/Show', ['eventBooking' => $event_booking]);
    }

    public function destroy(EventBooking $event_booking)
    {
        return to_route('event_bookings.index')
            ->with('message', "Cancelled " . $event_booking->event()->pluck('title')->first() . " for " . date("d F H:i", strtotime($event_booking->begins_at)));
    }
}
