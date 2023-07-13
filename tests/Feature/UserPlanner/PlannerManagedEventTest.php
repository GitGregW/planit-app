<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PlannerManagedEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_attendee_cannot_manage_events(): void
    {
        $user = $this->signIn('Attendee');
        $event = Event::factory()->state([
            'user_id' => $user->id
        ])->raw();
        $resource = $this->get('/events/create');
        // $resource->assertRedirect('/');
        $this->post('/events', $event)->assertRedirect('/');
    }

    public function test_a_planner_cannot_manage_events_of_others(): void
    {
        $this->signIn('Planner');
        $event = Event::factory()->raw();
        $this->post('/events', $event);

        $this->signIn('Planner');
        $event['address_line_1'] = "Old Street";
        $this->patch('/events/' . $event['slug'], $event)
            ->assertUnauthorized();
    }

    public function test_a_planner_can_create_an_event(): void
    {
        $user = $this->signIn('Planner');
        $event = [
            'user_id' => $user['id'],
            'slug' => 'new-event',
            'title' => 'New Event',
            'description' => 'Lorem Ipsum',
            'address_line_1' => 'foobar1',
            'address_line_2' => 'foobar2',
            'city' => 'foobar3',
            'county' => 'foobar4',
            'postcode' => 'FO12 3BA',
            'contact_mobile' => '01234',
            'contact_landline' => '06789',
            'rating' => 5
        ];
        $this->get('events/create')->assertStatus(200);

        $response = $this->post('/events', $event);
        $response->assertSessionHasNoErrors();
        /** Cannot assert redirect paths for InertiaJs tests. */
            // ->assertRedirect('/events/' . $event['slug']);

        $this->assertDatabaseHas('events', $event);

    }
    
    // public function test_a_planner_can_view_an_event(): void
    // {
    //     $this->withoutExceptionHandling();
    //     $user = User::factory()->create();
    //     $this->actingAs($user);
    //     $event = Event::factory()->for($user)->create();

    //     /** Cannot assert redirect paths for InertiaJs tests. */
    //     $this->get($event->path())
    //         ->assertSee($event->title);
    // }

    public function test_a_planner_can_update_an_event(): void
    {
        $user = $this->signIn('Planner');

        $eventRes = Event::factory()->for($user)->create();

        $event = Event::where('id', $eventRes->id)->first();
        $event->address_line_1 = "New Street";

        $this->patch($event->path(), $event->toArray());
        $this->assertDatabaseHas('events', ['address_line_1' => 'New Street']);
    }

    public function test_a_planner_can_remove_an_event(): void
    {
        $user = $this->signIn('Planner');
        $event = Event::factory()->for($user)->create();
        $this->delete($event->path(), $event->toArray());
        $this->assertDatabaseCount('events', 0);
    }

    public function test_a_planner_can_update_an_event_to_active(): void
    {
        $user = $this->signIn('Planner');

        $eventRes = Event::factory()->for($user)->state([
            'is_active' => 0
        ])->create();

        $event = Event::where('id', $eventRes->id)->first();
        $event->is_active = 1;

        $response = $this->patch('/events/' . $event->slug, $event->toArray());
        $this->assertDatabaseHas('events', ['is_active' => 1]);
    }

    public function test_a_user_can_index_events_with_a_stored_image(): void
    {
        $this->withoutExceptionHandling();
        Storage::fake('event_images');
        $user = $this->signIn('Planner');

        $eventRes = Event::factory()->for($user)->count(5)->create();

        $eventRes->map(function ($event){
            $file = UploadedFile::fake()->image($event->id . '/event' . $event->id . '.jpg');
            $this->post($event->path() . '/images', [
                    'src' => $file,
                ]);
        });
        
        $events = Event::get()->transform(function ($event){
            $file = Storage::disk('event_images')->files($event->id);
            $event->src = $file[0];
            return $event;
        });
    }
}
