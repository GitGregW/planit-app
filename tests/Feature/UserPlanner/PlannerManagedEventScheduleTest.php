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
        $this->withoutExceptionHandling();
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
        $this->withoutExceptionHandling();
        $user = $this->signIn('Planner');
        $days = collect(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'])->shuffle()->take(4);

        $eventRes = Event::factory()
            ->for($user)
            ->has(EventSchedule::factory()
                ->count(4)
                ->sequence(fn ($sequence) => ['day' => $days[$sequence->index]]))
                ->create();
        
        $event = Event::where('id', $eventRes->id)->with(['eventSchedules'])->first();

        $event->eventSchedules[2]->opening_time = "12:00:00";
        $event->eventSchedules[2]->closing_time = "14:55:00";
        $this->patch($event->path() . '/schedules', $event->eventSchedules->toArray());

        $this->assertDatabaseHas('event_schedules', ['closing_time' => '14:55:00']);
    }

    public function test_a_planner_can_remove_schedules_for_an_event(): void
    {
        $user = $this->signIn('Planner');
        $days = collect(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'])->shuffle()->take(4);

        $eventRes = Event::factory()
            ->for($user)
            ->has(EventSchedule::factory()
                ->count(4)
                ->sequence(fn ($sequence) => ['day' => $days[$sequence->index]]))
                ->create();

        $event = Event::where('id', $eventRes->id)->with(['eventSchedules'])->first();
        
        $this->actingAs($user)
            ->delete($event->path() . '/schedules', [$event->eventSchedules[2]->day]);

        $this->assertDatabaseCount('event_schedules', 3);
    }

    public function test_a_planner_can_modify_schedules_for_an_event(): void
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn('Planner');
        $days = ['Thursday','Friday','Saturday','Sunday'];
        
        $eventRes = Event::factory()
            ->for($user)
            ->has(EventSchedule::factory()
                ->count(4)
                ->sequence(fn ($sequence) => ['day' => $days[$sequence->index]]))
                ->create();
        
        $event = Event::where('id', $eventRes->id)->with(['eventSchedules'])->first();

        /** Changing an opening time */
        $event->eventSchedules[2]->opening_time = "12:00:00";
        $event->eventSchedules[2]->closing_time = "14:55:00";

        /** Adding an opening time */
        $event->eventSchedules->push([
            'event_id' => $event->id,
            'day' => 'Tuesday',
            'opening_time' => '08:00:00',
            'closing_time' => '10:00:00',
        ]);

        $this->patch($event->path() . '/schedules', $event->eventSchedules->toArray());
        /** Deleting an opening time */
        $this->delete($event->path() . '/schedules', [$event->eventSchedules[0]->day, $event->eventSchedules[3]->day]);

        $this->assertDatabaseCount('event_schedules', 3);
        $this->assertDatabaseHas('event_schedules', ['closing_time' => '14:55:00']);
    }
}
