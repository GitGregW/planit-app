<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Event;
use App\Models\EventImage;

class PlannerManagedEventImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_planner_can_add_images_to_an_event(): void
    {
        $this->signIn('Planner');
        $event = Event::factory()->create();

        $images = [
            ['src' => '/images/unsplash/events/adam-whitlock-I9j8Rk-JYFM-unsplash.jpg'],
            ['src' => '/images/unsplash/events/aedrian-BshM9VkzGw8-unsplash.jpg']
        ];
        $event->addImages($images);

        $image = '/images/unsplash/events/aedrian-BshM9VkzGw8-unsplash.jpg';
        $event->addImages($image);

        $this->assertDatabaseCount('event_images', 3);
    }

    public function test_a_planner_can_delete_images_from_an_event(): void
    {
        $this->signIn('Planner');
        $eventRes = Event::factory()
            ->has(EventImage::factory()
                ->count(4))
                ->create();
        
        $event = Event::where('id', $eventRes->id)->with(['eventImages'])->first();
        $event->deleteImages([$event->eventImages[2]->id]);

        $event->deleteImages([
            $event->eventImages[0]->id,
            $event->eventImages[3]->id
        ]);

        $this->assertDatabaseCount('event_images', 1);
    }
}
