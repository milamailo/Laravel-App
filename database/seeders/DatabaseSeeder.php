<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Achievemenets;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $lessons = Lesson::factory()
            ->count(20)
            ->create();

        Achievemenets::factory()
            ->count(14) // You can adjust the count as needed
            ->create();
    }
}
