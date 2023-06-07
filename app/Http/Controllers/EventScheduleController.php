<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\EventSchedule;

class EventScheduleController extends Controller
{
    public function update(Request $request){
        $event = $request->toArray();
        foreach($event["event_schedules"] as $event_schedule){
            EventSchedule::where('id', $event_schedule['id'])->update($event_schedule);
        }
        return Inertia::render('Events/Show', ['event' => $event]);
        // return to_route('events.show', [$event]);
    }
}
