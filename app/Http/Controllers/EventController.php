<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function create(){
        if(Auth::User()->userGroup->name !== 'Planner') return to_route('home');
        return Inertia::render('Events/Create');
    }

    public function store(Request $request){
        if(Auth::User()->userGroup->name !== 'Planner') return to_route('home');
        $event = Event::create($request->all());
        return Inertia::render('Events/Show', ['event' => $event]);
    }

    public function show($event){
        return Inertia::render('Events/Show', ['event' => $event]);
    }

    public function edit(){
        if(Auth::User()->userGroup->name !== 'Planner') return to_route('home');
        return Inertia::render('Events/Edit');
    }

    public function update(Request $request){
        if(Auth::User()->userGroup->name !== 'Planner') return to_route('home');
        elseif(Auth::User()->id !== $request->user_id) return abort(401, "You do not have permissions to ammend this event.");
        $event = Event::where('id', $request->id)->update($request->toArray());
        return Inertia::render('Events/Show', ['event' => $event]);
    }

    public function destroy(Request $request){
        if(Auth::User()->userGroup->name !== 'Planner') return to_route('home');
        Event::where('id', $request->id)->delete();
        return Inertia::render('Events/Index');
    }
}
