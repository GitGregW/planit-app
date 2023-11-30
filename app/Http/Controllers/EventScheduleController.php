<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\EventSchedule;
use App\Models\Event;

class EventScheduleController extends Controller
{
    public function create(Event $event)
    {
        $schedules = $event->eventSchedules()->get()->keyBy('day');
        return Inertia::render('EventSchedules/Create', [
            'eventSlug' => $event->slug, 'schedules' => $schedules
        ]);
    }

    public function store(Event $event, Request $request)
    {
        // $schedules = $request->toArray();
        $event_schedules = $request->validate([
            '*.opening_time' => 'required_with:*.closing_time|before:*.closing_time',
            '*.closing_time' => 'required_with:*.opening_time|after:*.opening_time',
        ]);
        $event->eventSchedules()->createMany($event_schedules);
        // return json_encode("Schedules added.");
    }

    public function update(Event $event, Request $request)
    {
        $event_schedules = $request->validate([
            '*.opening_time' => 'required_with:*.closing_time|before:*.closing_time',
            '*.closing_time' => 'required_with:*.opening_time|after:*.opening_time',
            '*.day' => 'required',
            '*.custom_date' => 'required_without:*.day',
            '*.custom_repeat' => 'nullable'
        ]);
        
        foreach($event_schedules as $event_schedule){
            EventSchedule::updateOrInsert(['event_id' => $event['id'], 'day' => $event_schedule['day']], $event_schedule);
        }
        // return json_encode("Schedules modified.");
    }

    public function destroy(Event $event, Request $request)
    {
        $event_schedules = $request->toArray();
        if($event_schedules) $event->eventSchedules()->whereIn('day', $event_schedules)->delete();
        // return json_encode("Schedules deleted.");
    }
}
