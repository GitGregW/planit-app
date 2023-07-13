<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Event;
use App\Models\EventImage;

class PlannerManagedEventImageTest extends TestCase
{
    use RefreshDatabase;

    // public function test_a_planner_can_add_images_to_an_event(): void
    // {
    //     $user = $this->signIn('Planner');
    //     $event = Event::factory()->for($user)->create();

    //     $images = [
    //         ['src' => '/images/unsplash/events/adam-whitlock-I9j8Rk-JYFM-unsplash.jpg'],
    //         ['src' => '/images/unsplash/events/aedrian-BshM9VkzGw8-unsplash.jpg']
    //     ];
    //     $this->actingAs($user)
    //         ->post($event->path() . '/images', $images);

    //     $image = ['src' => '/images/unsplash/events/aedrian-BshM9VkzGw8-unsplash.jpg'];
    //     $this->actingAs($user)
    //         ->post($event->path() . '/images', $image);

    //     $this->assertDatabaseCount('event_images', 3);
    // }

    // public function test_a_planner_can_delete_images_from_an_event(): void
    // {
    //     $user = $this->signIn('Planner');
    //     $eventRes = Event::factory()
    //         ->for($user)
    //         ->has(EventImage::factory()
    //             ->count(4))
    //             ->create();
        
    //     $event = Event::where('id', $eventRes->id)->with(['eventImages'])->first();
    //     $this->actingAs($user)
    //         ->delete($event->path() . '/images/' . $event->eventImages[2]->id);
    //     $this->actingAs($user)
    //         ->delete($event->path() . '/images/' . $event->eventImages[0]->id);
    //     $this->actingAs($user)
    //         ->delete($event->path() . '/images/' . $event->eventImages[3]->id);
    //     // $this->actingAs($user)
    //     //     ->delete($event->path() . '/images/' . [$event->eventImages[2]->id, $event->eventImages[3]->id]);

    //     $this->assertDatabaseCount('event_images', 1);
    // }

    public function test_images_can_be_uploaded_related_to_an_event(): void
    {
        Storage::fake('event_images');
        $user = $this->signIn('Planner');
        $event = Event::factory()->for($user)->create();
 
        $file = UploadedFile::fake()->image($event['id'] . '/event.jpg');
 
        $response = $this->actingAs($user)
            ->post($event->path() . '/images', [
                'src' => $file,
            ]);

        Storage::disk('event_images')->assertExists($event['id'] . '/' . $file->hashName());
    }

    public function test_a_planner_can_delete_images_from_an_event(): void
    {
        Storage::fake('event_images');
        $user = $this->signIn('Planner');
        $event = Event::factory()->for($user)->create();
 
        $this->post($event->path() . '/images', [
            'src' => UploadedFile::fake()->image($event['id'] . '/image1.jpg'),
        ]);
        $this->post($event->path() . '/images', [
            'src' => $file = UploadedFile::fake()->image($event['id'] . '/image2.jpg'),
        ]);
        $this->post($event->path() . '/images', [
            'src' => UploadedFile::fake()->image($event['id'] . '/image3.jpg'),
        ]);

        Storage::disk('event_images')->assertExists($event['id'] . '/' . $file->hashName());
    }
}
