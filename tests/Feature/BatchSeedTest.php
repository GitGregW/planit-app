<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class BatchSeedTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_batch_create_events_for_a_planner_user(): void
    {
        $planner = \App\Models\UserGroup::where('name', 'Planner')->first();
        $event_planners = \App\Models\User::factory(3)->for($planner)->create();
        $days_collection = collect([]);
        $events_count = 4;
        $event_schedules_count = 4;

        foreach($event_planners as $event_planner){
            
            /** Prepare a collection of days for Event Schedules to sequence over */
            for($i=0;$i < $events_count; $i++){
                $days = collect(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'])->shuffle()->take(4);

                foreach($days as $day){
                    $days_collection->push($day);
                }
            }

            \App\Models\Event::factory($events_count)
                ->state(['is_active' => 1])
                ->for($event_planner)
                ->has(\App\Models\EventSchedule::factory()
                    ->count($event_schedules_count)
                    ->sequence(fn ($sequence) => ['day' => $days_collection[$sequence->index]]))
                    ->create();
            
            \App\Models\Event::factory(2)
                ->state(['is_active' => 0])
                ->for($event_planner)
                ->create();
        }

        $this->assertDatabaseCount('users', 3);
        $this->assertDatabaseCount('events', 18);
        $this->assertDatabaseCount('event_schedules', 48);
    }

    public function test_batch_create_event_bookings_for_an_attendee_user(): void
    {
        $this->withoutExceptionHandling();

        $count=0;
        $attendee = \App\Models\UserGroup::where('name', 'Attendee')->first();
        $planner = \App\Models\UserGroup::where('name', 'Planner')->first();
        $event_attendees = \App\Models\User::factory(2)
            ->for($attendee)
            ->has(\App\Models\EventBooking::factory(5))
            ->create();

        $attendee_users = \App\Models\User::where(['user_group_id' => 1])->get();
        $planner_users = \App\Models\User::where(['user_group_id' => 2])->get();
        $this->assertCount(2, $attendee_users);
        $this->assertCount(10, $planner_users);
        $this->assertDatabaseCount('users', 12);
        $this->assertDatabaseCount('event_bookings', 10);
    }

    public function test_batch_event_bookings_from_available_active_events(): void
    {
        $this->withoutExceptionHandling();
        
        $attendee = \App\Models\UserGroup::where('name', 'Attendee')->first();
        $planner = \App\Models\UserGroup::where('name', 'Planner')->first();
        $event_planners = \App\Models\User::factory(3)->for($planner)->create();
        
        foreach($event_planners as $event_planner){
            \App\Models\Event::factory(10)
                ->state(['is_active' => 1])
                ->for($event_planner)
                ->create();
        }

        $active_events = \App\Models\Event::where(['is_active' => 1])->get();
        \App\Models\User::factory(6)
            ->for($attendee)
            ->has(\App\Models\EventBooking::factory(fake()->numberBetween(1, 9))
                ->state(new Sequence(
                    fn (Sequence $sequence) => [
                        'event_id' => $active_events->random()->id,
                    ],
                )))
            ->create();

        // dd(\App\Models\EventBooking::all());
    }
}
