<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EventImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $path = '/images/unsplash/events/';
        $jpegs = ['aedrian-BshM9VkzGw8-unsplash.jpg', 'aedrian-slIzL6Rk4xI-unsplash.jpg', 'al-nik-K0mrkZiTbfQ-unsplash.jpg',
            'frederick-medina-HLG35jI85V8-unsplash.jpg', 'john-thomas-LtE6W_JVTGc-unsplash.jpg', 'adam-whitlock-I9j8Rk-JYFM-unsplash.jpg',
            'dillon-wanner-W6hgIVl7-xM-unsplash.jpg', 'dillon-wanner-Bq0IG3mu-WY-unsplash.jpg', 'jason-leung-4BKiS_BgOwI-unsplash.jpg',
            'alex-suprun-AvIEqv1iY-4-unsplash.jpg', 'ronan-furuta-JsV2hmFY3Jc-unsplash.jpg', 'ronan-furuta-wqodnf_H6zs-unsplash.jpg',
            'markus-spiske-qhgtBITGZeI-unsplash.jpg', 'markus-spiske-i1ksCIjm0dQ-unsplash.jpg', 'gama-films-LHhzTa93XH0-unsplash.jpg',
            'pradeep-charles-1AJZQiJ30ag-unsplash.jpg', 'fernando-lavin-fi5YSQfxbVk-unsplash.jpg', 'shanna-camilleri-ljNQxfyN7AM-unsplash.jpg'];

        return [
            'event_id' => Event::factory(),
            'src' => $path . $this->faker->randomElement($jpegs),
        ];
    }
}
