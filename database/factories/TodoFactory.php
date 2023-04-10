<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name(),
            'description' => $this->faker->paragraph,
            'created_at' => $this->faker->dateTimeBetween(date("Y-m-d", strtotime("-2 week")), date("Y-m-d", strtotime("-1 week"))),
            'updated_at' => $this->faker->dateTimeBetween(date("Y-m-d", strtotime("-6 days")), date("Y-m-d", strtotime("-1 day"))),
            'done' => false,
            'deleted_at' => null,
        ];
    }

    /**
     * Indicate that the model should be deleted.
     *
     * @return static
     */
    public function deleted()
    {
        return $this->state(function (array $attributes) {
            return [
                'deleted_at' => $this->faker->dateTimeBetween(date("Y-m-d", strtotime("-12 hours")), date("Y-m-d")),
            ];
        });
    }
    
    public function neverUpdated()
    {
        return $this->state(function (array $attributes) {
            return [
                'updated_at' => null,
            ];
        });
    }

}
