<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class EventImageController extends Controller
{
    public function store(Event $event, Request $request)
    {
        if(Auth::User()->userGroup->name !== 'Planner') return to_route('home');
        $event->addImages($request);
        return Inertia::render('Events/Show', ['event' => $event]);
    }

    public function destroy(Event $event, Request $request)
    {
        if(Auth::User()->userGroup->name !== 'Planner') return to_route('home');
        $event->deleteImages($request);
        return Inertia::render('Events/Show', ['event' => $event]);
    }
}
