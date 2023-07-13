<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Event;
use App\Models\Tag;

class EventTagController extends Controller
{
    public function store(Event $event, Request $request){
        if(count($request->all()) > 1) $event->tags()->createMany($request->all());
        else $event->tags()->create($request->all());
    }
    
    public function destroy(Event $event, Request $request)
    {
        $event->tags()->detach($request->all());
        return Inertia::render('Events/Show', ['event' => $event]);
    }
}
