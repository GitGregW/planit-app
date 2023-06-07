<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\UserGroupSeeder;
use App\Models\User;
use App\Models\Event;
use App\Models\EventBooking;
use App\Models\UserGroup;

class BookingEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_planner_cannot_book_an_event(): void
    {
        $planner = $this->signIn('Planner');
        $event = Event::factory()->create();

        $eventBooking = EventBooking::factory()
            ->state([
                'user_id' => $planner,
                'event_id' => $event,
            ])
            ->raw();

        $this->actingAs($planner)
            ->post('/event-bookings', $eventBooking)
            ->assertRedirect('/');
        
        $this->assertDatabaseCount('event_bookings', 0);
    }

    public function test_an_attendee_can_book_an_event(): void
    {
        $attendee = $this->signIn('Attendee');
        $event = Event::factory()->create();

        $eventBooking = EventBooking::factory()
            ->state([
                'user_id' => $attendee,
                'event_id' => $event,
            ])
            ->raw();

        $response = $this
            ->actingAs($attendee)
            ->post('/event-bookings', $eventBooking);
        $eventBooking = EventBooking::first();

        $response->assertRedirect('/event-bookings/' . $eventBooking->id);
        $this->assertDatabaseCount('event_bookings', 1);
    }

    public function test_an_attendee_can_modify_an_event_booking(): void
    {
        $attendee = $this->signIn('Attendee');
        
        $event = Event::factory()->create();
        $eventBooking = $attendee->eventBookings()->create(
            EventBooking::factory()
            ->for($event)
            ->raw()
        );

        $eventBooking->begins_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $eventBooking->ends_at = date('Y-m-d H:i:s', strtotime('+3 hour'));
        $this->actingAs($attendee)
            ->patch('/event-bookings/' . $eventBooking->id, $eventBooking->toArray())
            ->assertOk();
    }

    public function test_an_attendee_can_cancel_an_event_booking(): void
    {
        $attendee = $this->signIn('Attendee');
        
        $eventBooking = EventBooking::factory()
            ->for($attendee)
            ->count(5)
            ->create();
        
        $this->actingAs($attendee)
            ->delete('/event-bookings/' . $eventBooking[3]->id);
        $this->assertDatabaseCount('event_bookings', 4);
    }
}
