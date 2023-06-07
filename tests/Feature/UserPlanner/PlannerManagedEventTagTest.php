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
        $this->signIn('Planner');
        $event = Event::factory()->create();

        $tags = [
            ['name' => 'Outdoors'],
            ['name' => 'Watersports'],
            ['name' => 'Daytime'],
            ['name' => 'Accessibility'],
            ['name' => 'Active'],
            ['name' => 'Instructor']
        ];

        $event->addTags($tags);

        $this->assertDatabaseCount('event_tag', 6);
    }

    public function test_a_planner_can_delete_tags_from_an_event(): void
    {
        $this->signIn('Planner');

        $eventRes = Event::factory()
            ->hasAttached(Tag::factory()
                ->count(6))
                ->create();
        
        $event = Event::where('id', $eventRes->id)->with(['tags'])->first();

        $event->deleteTags([$event->tags[2]->id]);
        $event->deleteTags([$event->tags[3]->id, $event->tags[4]->id]);

        $this->assertDatabaseCount('event_tag', 3);
    }
}
