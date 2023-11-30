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
    
    /** REMOVED: No requirement to store event images in database where it can be indexed through file storage  */
    // public function test_an_event_image_belongs_to_event(): void
    // {
    //     $eventImage = EventImage::factory()->create();
    //     $this->assertInstanceOf(Event::class, $eventImage->event);
    // }
}
