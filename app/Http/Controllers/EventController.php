<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(){
        Auth::user()->userGroup()->first()->name == "Planner"
            ? $events = Auth::User()->events()->get()->transform(function ($event){
                $file = Storage::disk('event_images')->files($event->id);
                $file ? $event->src = $file[0] : $event->src = '0/cZ5V1tlwn8vtCm4y0TmplP3wFuXPVnwlutSy4HVT.jpg';
                return $event;
            })
            : $events = Event::get();
        
        // $events->tap(function ($event){
        //     append(['src' => $event->getImages()]);
        // });
        
        return Inertia::render('Events/Index', ['events' => $events]);
    }

    public function create(){
        return Inertia::render('Events/Create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'title' => 'required|unique:events|max:24',
            'description' => 'required',
            'address_line_1' => 'required',
            'address_line_2' => 'required',
            'city' => 'required',
            'county' => 'required',
            'postcode' => 'required',
            'contact_mobile' => '',
            'contact_landline' => '',
        ]);
        $validated['user_id'] = Auth::user()->id;
        $validated['slug'] = $request['title'] ?
            strtolower(str_replace(' ', '_', $request['title'])) : '';

        $event = Event::create($validated);
        return Inertia::render('Events/Edit', ['event' => $event]);
    }

    public function show(Event $event){
        $images = $event->getImages();
        $schedules = $event->eventSchedules()->get()->keyBy('day');

        return Inertia::render('Events/Show',
            ['event' => $event, 'images' => $images, 'schedules' => $schedules]
        );
    }

    public function edit(Event $event){
        $images = $event->getImages();
        $schedules = $event->eventSchedules()->get()->keyBy('day');

        return Inertia::render('Events/Edit',
            ['event' => $event, 'images' => $images, 'schedules' => $schedules]
        );
    }

    public function update(Event $event, Request $request){
        if(Auth::User()->id !== $event->user_id) return abort(401, "You do not have permissions to ammend this event.");
        Event::where('id', $event->id)->update($request->toArray());
        
        return to_route('events.edit', $event);
    }

    public function destroy(Event $event){
        Event::where('id', $event->id)->delete();
        $event->deleteImages();
        return to_route('events.index');
    }
}
