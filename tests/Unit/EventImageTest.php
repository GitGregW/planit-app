<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Event;
use App\Models\EventImage;

class EventImageTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_an_event_image_belongs_to_event(): void
    {
        $eventImage = EventImage::factory()->create();
        $this->assertInstanceOf(Event::class, $eventImage->event);
    }
}
