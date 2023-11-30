<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Database\Eloquent\Factories\Sequence;

class FactoryLatinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** Faker latin Planner Events */
        $random_images = [
            "images/unsplash/faker_events/adam-whitlock-I9j8Rk-JYFM-unsplash.jpg",
            "images/unsplash/faker_events/aedrian-BshM9VkzGw8-unsplash.jpg",
            "images/unsplash/faker_events/aedrian-slIzL6Rk4xI-unsplash.jpg",
            "images/unsplash/faker_events/dillon-wanner-Bq0IG3mu-WY-unsplash.jpg",
            "images/unsplash/faker_events/dillon-wanner-W6hgIVl7-xM-unsplash.jpg",
            "images/unsplash/faker_events/fernando-lavin-fi5YSQfxbVk-unsplash.jpg",
            "images/unsplash/faker_events/isuru-ranasingha-RpzTftQzkUo-unsplash.jpg",
            "images/unsplash/faker_events/john-thomas-LtE6W_JVTGc-unsplash.jpg",
            "images/unsplash/faker_events/liliana-morillo-ditAtitbnBU-unsplash.jpg",
            "images/unsplash/faker_events/mauro-lima-OvZABiHk3Fk-unsplash.jpg",
            "images/unsplash/faker_events/mika-luoma-imtdvAUCbGU-unsplash.jpg",
            "images/unsplash/faker_events/ronan-furuta-JsV2hmFY3Jc-unsplash.jpg",
            "images/unsplash/faker_events/sajad-baharvandi-8Vx7BUaCo8A-unsplash.jpg",
            "images/unsplash/faker_events/shanna-camilleri-ljNQxfyN7AM-unsplash.jpg",
            "images/unsplash/faker_events/wolfgang-hasselmann-zSrJCTMg-e8-unsplash.jpg"
        ];
        $attendee = \App\Models\UserGroup::where('name', 'Attendee')->first();
        $planner = \App\Models\UserGroup::where('name', 'Planner')->first();
        $event_planners = \App\Models\User::factory(3)->for($planner)->create();
        $days_collection = collect([]);

        foreach($event_planners as $event_planner){
            
            $events_count = fake()->numberBetween(2, 6);
            $event_schedules_count = 4;
            /** Prepare a collection of days for Event Schedules to sequence over */
            for($i=0;$i < $events_count; $i++){
                $days = collect(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'])->shuffle()->take($event_schedules_count);

                foreach($days as $day){
                    $days_collection->push($day);
                }
            }

            $active_events = \App\Models\Event::factory($events_count)
                ->state(['is_active' => 1])
                ->for($event_planner)
                ->has(\App\Models\EventSchedule::factory()
                    ->count($event_schedules_count)
                    ->sequence(fn ($sequence) => ['day' => $days_collection[$sequence->index]]))
                    ->create();
            
            \App\Models\Event::factory(fake()->numberBetween(1, 4))
                ->state(['is_active' => 0])
                ->for($event_planner)
                ->create();

            foreach($active_events as $active_event){
                Storage::disk('event_images')->putFile($active_event->id, new File(public_path(fake()->randomElement($random_images))));
                Storage::disk('event_images')->putFile($active_event->id, new File(public_path(fake()->randomElement($random_images))));
            }
        }
        
        $active_events = \App\Models\Event::where(['is_active' => 1])->get();
        $event_attendees = \App\Models\User::factory(6)
            ->for($attendee)
            ->has(\App\Models\EventBooking::factory(fake()->numberBetween(1, 9))
                ->state(new Sequence(
                    fn (Sequence $sequence) => [
                        'event_id' => $active_events->random()->id
                    ],
                )))
            ->create();
            
    }
}
