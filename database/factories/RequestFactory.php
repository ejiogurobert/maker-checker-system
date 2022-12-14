<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $data = json_encode([

            [

                'first_name' =>  fake()->name(),

                'last_name' =>   fake()->name(),

                'email' =>      fake()->safeEmail(),

            ],

            [

                'first_name' =>  fake()->name(),

                'last_name' =>   fake()->name(),

                'email' =>      fake()->safeEmail(),

            ],

        ]);

        return [

            'request_type' => fake()->randomElement(['create', 'update', 'decline']),

            'user_id' =>     auth()->user()->id,

            'endorsee_id' =>      auth()->user()->id,

            'data' =>    $data,






        ];
    }
}
