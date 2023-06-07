<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\UserGroupSeeder;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Event;
use App\Models\EventImage;
use App\Models\Category;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_a_path(): void
    {
        $this->seed(UserGroupSeeder::class);
        $userGroup = UserGroup::where('name', 'Planner')->first();
        $user = User::factory()->for($userGroup)->create();

        $event = Event::factory()->create([
            'user_id' => $user['id'],
            'slug' => 'new_event'
        ]);

        $this->assertEquals('/events/' . $event->slug, $event->path());
    }

    // public function test_categories_belongs_to_many_events(): void
    // {
    //     $category = Category::factory()->create();
    //     $this->assertInstanceOf(Event::class, $category->event);
    // }

    public function test_an_event_belongs_to_user(): void
    {
        $event = Event::factory()->create();
        $this->assertInstanceOf(User::class, $event->user);
    }
}
