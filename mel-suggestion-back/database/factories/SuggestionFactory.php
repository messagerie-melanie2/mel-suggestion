<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SuggestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
      return [
        'title' => $this->faker->sentence(),
        'description' => $this->faker->paragraph(),
        'user_email' => $this->faker->email(),
        'state' => 'vote',
        'instance' => 'bnum'        
    ];
    }
}
