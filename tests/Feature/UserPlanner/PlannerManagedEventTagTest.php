<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Tag;

class PlannerManagedEventTagTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_planner_can_create_an_event_with_tags(): void
    {
        $user = $this->signIn('Planner');
        $event = Event::factory()->for($user)->create();

        $tags = [
            ['name' => 'Outdoors'],
            ['name' => 'Watersports'],
            ['name' => 'Daytime'],
            ['name' => 'Accessibility'],
            ['name' => 'Active'],
            ['name' => 'Instructor']
        ];

        /** To do: Assign slug through model validation of sorts */
        foreach($tags as $key => $tag){
            $tags[$key]['slug'] = strtolower(str_replace(' ','-',$tag['name']));
        }

        $this->actingAs($user)
            ->post($event->path() . '/tags', $tags);

        $this->assertDatabaseCount('event_tag', 6);
    }

    public function test_a_planner_can_delete_tags_from_an_event(): void
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn('Planner');

        $eventRes = Event::factory()
            ->for($user)
            ->hasAttached(Tag::factory()
                ->count(6))
                ->create();
        
        $event = Event::where('id', $eventRes->id)->with(['tags'])->first();

        $this->actingAs($user)
            ->delete($event->path() . '/tags/', [$event->tags[2]->id, $event->tags[3]->id]);
        $this->actingAs($user)
            ->delete($event->path() . '/tags/', [$event->tags[4]->id]);

        $this->assertDatabaseCount('event_tag', 3);
    }
}
