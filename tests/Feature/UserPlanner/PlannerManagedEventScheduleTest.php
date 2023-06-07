<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Event;
use App\Models\EventSchedule;

class PlannerManagedEventScheduleTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_a_planner_can_add_schedules_for_an_event(): void
    {
        $this->signIn('Planner');
        $days = collect(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'])->shuffle()->take(4);

        Event::factory()
            ->has(EventSchedule::factory()
                ->count(4)
                ->sequence(fn ($sequence) => ['day' => $days[$sequence->index]]))
                ->create();

        $this->assertDatabaseCount('event_schedules', 4);
    }

    public function test_a_planner_can_update_schedules_for_an_event(): void
    {
        $this->signIn('Planner');
        $days = collect(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'])->shuffle()->take(4);

        $eventRes = Event::factory()
            ->has(EventSchedule::factory()
                ->count(4)
                ->sequence(fn ($sequence) => ['day' => $days[$sequence->index]]))
                ->create();
        
        $event = Event::where('id', $eventRes->id)->with(['eventSchedules'])->first();

        $event->eventSchedules[2]->day = "Easter Bank Holiday";
        $this->patch($event->path() . '/schedules', $event->toArray());

        $this->assertDatabaseHas('event_schedules', ['day' => 'Easter Bank Holiday']);
    }

    public function test_a_planner_can_remove_schedules_for_an_event(): void
    {
        $this->signIn('Planner');
        $days = collect(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'])->shuffle()->take(4);

        $eventRes = Event::factory()
            ->has(EventSchedule::factory()
                ->count(4)
                ->sequence(fn ($sequence) => ['day' => $days[$sequence->index]]))
                ->create();
        
        $event = Event::where('id', $eventRes->id)->with(['eventSchedules'])->first();

        $event->deleteEventSchedule($event->eventSchedules[2]->toArray());

        $this->assertDatabaseCount('event_schedules', 3);
    }
}
