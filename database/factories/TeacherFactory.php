<?php

namespace Database\Factories;

use App\Enums\Agency;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'agency' => Agency::BIALYSTOK
        ];
    }
}
