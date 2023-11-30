<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserGroupSeeder::class,
        ]); 
    }
}
