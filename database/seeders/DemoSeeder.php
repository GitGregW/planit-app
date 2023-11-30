<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        Storage::deleteDirectory('event_images');
        Storage::disk('event_images')->putFile(0, new File(public_path('images/unsplash/planit/oxana-v-qoAIlAmLJBU-unsplash.jpg')));

        $this->call([
            UserGroupSeeder::class,
            FactoryLatinSeeder::class,
            FactoryEnglishSeeder::class,
        ]); 
    }
}
