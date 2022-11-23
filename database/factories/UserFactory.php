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
        $createdBy = Str::uuid();
        return [
            'EmployeeNumber'    => "EPLDT-".fake()->ean8(),
            'FirstName'         => fake()->firstName(),
            'MiddleName'        => fake()->lastName(),
            'LastName'          => fake()->lastName(),
            'Title'             => fake()->jobTitle(),
            'About'             => fake()->paragraph(3),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make('12345678'),
            'Created_By_Id'     => $createdBy,
            'Updated_By_Id'     => $createdBy,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
