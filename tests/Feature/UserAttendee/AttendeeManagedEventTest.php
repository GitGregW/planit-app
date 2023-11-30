<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Event;

class AttendeeManagedEventTest extends TestCase
{
    public function test_an_attendee_cannot_manage_events(): void
    {
        $attendee = $this->signIn('Attendee');
        $event = Event::factory()->for($attendee)->raw();
        $resource = $this->get('/events/create')
            ->assertRedirect(RouteServiceProvider::HOME);
        $this->post('/events', $event)
            ->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_an_attendee_cannot_manage_inactive_events(): void
    {
        $planner = $this->signIn('Planner');
        $event = Event::factory()->state(['is_active' => false])->for($planner)->create();

        $this->signIn('Attendee');
        $response = $this->get($event->path())
            ->assertNotFound();
    }
}
