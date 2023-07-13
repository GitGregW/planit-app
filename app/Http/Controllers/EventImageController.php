<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\Event;
use App\Models\EventImage;

class EventImageController extends Controller
{
    public function create(Event $event){
        $images = $event->getImages();
        return Inertia::render('EventImages/Create', [
            'eventSlug' => $event->slug, 'images' => $images
        ]);
    }

    public function index(Event $event){
        $images = $event->getImages();
        return Inertia::render('EventImages/Index', [
            'event' => $event, 'images' => $images
        ]);
    }

    public function store(Event $event, Request $request)
    {
        $request->file('src')->store($event->id, 'event_images');
        $images = $event->getImages();
    }

    public function destroy(Event $event, $index = null)
    {
        $images = $event->getImages();
        Storage::disk('event_images')->delete($images[$index]);
    }
}
