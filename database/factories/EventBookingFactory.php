<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;
use App\Models\User;
use DateTime;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EventBookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $beginsAt = $this->faker->dateTimeBetween('+2 days', '+3 weeks');
        $endsAt = new DateTime($beginsAt->format('Y-m-d H:i:s'));
        $endsAt->modify('+' . ($this->faker->numberBetween(2,48) * 30) . ' minutes');
        
        return [
            'event_id' => Event::factory(),
            'user_id' => User::factory(),
            'begins_at' => $beginsAt->format('Y-m-d H:i:s'),
            'ends_at' => $endsAt->format('Y-m-d H:i:s'),
            'price' => $this->faker->randomFloat(1, 5, 80),
            'discount' => $this->faker->randomFloat(2, 0, 0.3),
        ];
    }
}
