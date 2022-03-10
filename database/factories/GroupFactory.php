<?php

namespace Database\Factories;

use App\Enums\Agency;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $group_names = [
            'Wizażystki 1',
            'Wizażystki 2',
            'Wizażystki 3',
            'Fryzjerstwo 1',
            'Fryzjerstwo 2',
            'Fryzjerstwo 3',
            'Dziennikarstwo 1',
            'Dziennikarstwo 2',
            'Dziennikarstwo 3',

        ];
        return [
            'name' => $group_names[array_rand($group_names)],
            'agency' => Agency::BIALYSTOK
        ];
    }
}
