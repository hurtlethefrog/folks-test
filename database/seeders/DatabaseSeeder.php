<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Todo::factory(10)->create();
        \App\Models\Todo::factory(10)->state(new Sequence(
            ['done' => true],
            ['done' => false]
        ))->create();
        \App\Models\Todo::factory(5)->deleted()->create();
        \App\Models\Todo::factory(5)->deleted()->neverUpdated()->create();
    }
}
