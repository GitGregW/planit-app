<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EventScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday','Bank Holiday','other'];
        $key = $this->faker->numberBetween(0,6);
        
        return [
            'event_id' => Event::factory(),
            'day' => $days[$key],
            'opening_time' => $this->faker->time('H:00:00'),
            'closing_time' => $this->faker->time('H:00:00'),
            'max_capacity' => $this->faker->numberBetween(20, 100),
        ];
    }
}
