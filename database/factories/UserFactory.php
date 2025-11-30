<?php

namespace Database\Factories;

use App\Models\GovernmentAgencie;
use App\Models\User;
// use App\Models\GovernmentAgency;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $agencyId = GovernmentAgencie::inRandomOrder()->value('id');

        if (!$agencyId) {
            $agencyId = GovernmentAgencie::factory()->create()->id;
        }

        return [
            'agency_id'   => $agencyId,
            'First_name'  => fake()->firstName(),
            'Last_name'   => fake()->lastName(),
            'email'       => fake()->unique()->safeEmail(),
            'phone_number' => fake()->unique()->phoneNumber(),
            'password'    => Hash::make("password"),
            'role'        => fake()->randomElement(['super_admin', 'supervisor', 'employee', 'client']),
        ];
    }
}
