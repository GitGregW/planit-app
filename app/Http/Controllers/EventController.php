<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Event::class, 'event');
    }

    public function index(){
        $events = Auth::user()->userGroup()->first()->name == "Planner"
            ? Auth::User()->events()->latest()->paginate(6)
            : Event::where('is_active', 1)->latest()->paginate(6);

        $events->transform(function ($event){
            $files = $event->getImages();
            $files ? $event->src = $files[0] : $event->src = Storage::disk('event_images')->files('0');
            return $event;
        });
        
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
            strtolower(str_replace(' ', '-', $request['title'])) : '';

        $event = Event::create($validated);
        return to_route('events.edit', $event);
    }

    public function show(Event $event){
        $images = $event->getImages();
        $schedules = $event->eventSchedules()->get()->keyBy('day');
        $token = file_get_contents(public_path('mapbox_token.txt'));

        return Inertia::render('Events/Show',
            ['event' => $event, 'images' => $images, 'schedules' => $schedules, 'mapboxToken' => $token]
        );
    }

    public function edit(Event $event){
        // if(Auth::User()->id !== $event->user_id) return abort(401, "You do not have permissions to ammend this event.");
        $images = $event->getImages();
        $schedules = $event->eventSchedules()->get()->keyBy('day');

        return Inertia::render('Events/Edit',
            ['event' => $event, 'images' => $images, 'schedules' => $schedules]
        );
    }

    public function update(Event $event, Request $request){
        // if(Auth::User()->id !== $event->user_id) return abort(401, "You do not have permissions to ammend this event.");
        Event::where('id', $event->id)->update($request->toArray());
        
        return to_route('events.edit', $event);
    }

    public function destroy(Event $event){
        // if(Auth::User()->id !== $event->user_id) return abort(401, "You do not have permissions to ammend this event.");
        Event::where('id', $event->id)->delete();
        $event->deleteImages();
        return to_route('events.index');
    }
}
