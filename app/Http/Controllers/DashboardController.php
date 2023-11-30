<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Event;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'active' => ['nullable'],
            'inactive' => ['nullable'],
            'sort' => ['nullable'],
        ]);
        $validated = $validator->validated();

        $events = Auth::User()->events()
            ->select(['events.id', 'events.is_active', 'events.title', 'events.slug', 'events.created_at'])
            ->when(isset($validated["active"]), fn ($query) => $query->where('is_active', 1))
            ->when(isset($validated["inactive"]), fn ($query) => $query->where('is_active', 0))
            ->orderBy('events.id', isset($validated["sort"]) ? $validated["sort"] : 'asc')
            ->paginate(6);

        $events->transform(function ($event) {
            $files = $event->getImages();
            $event['src'] = $files ? $files[0] : Storage::disk('event_images')->files('0');
            return $event;
        });

        return Inertia::render('DashboardPlanner', ['events' => $events]);
    }
}
