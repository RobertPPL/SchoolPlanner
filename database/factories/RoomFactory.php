<?php

namespace Database\Factories;

use App\Enums\Agency;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->numberBetween(1, 1000),
            'agency' => Agency::BIALYSTOK
        ];
    }
}
