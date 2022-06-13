<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $start_date = $this->faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now')->format("Y-m-d");
        $end_date = $this->faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now')->format("Y-m-d");
        $prezent = $this->faker->boolean();
        if ($prezent == true) {
            $end_date = date('y-m-d');
        }
        if ($start_date > $end_date) {
            $aleatorie = $end_date;
            $end_date = $start_date;
            $start_date = $aleatorie;
        }
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => $this->faker->date('now'),
            'password' => Hash::make('1234'), // password
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'date_birth' => $start_date,
            'date_finish' => $end_date,
            'prezent_activity' => false,
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'user_type' => ('student0'),
            'avatar_path' => 'https://bootdey.com/img/Content/avatar/avatar3.png',
            // $prezent_checked = faker->boolean(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
