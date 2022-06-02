<?php

namespace Database\Factories;

use App\Models\Suggestion;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_email' => $this->faker->email(),
            'suggestion_id' => Suggestion::all()->random()->id
        ];
    }
}
