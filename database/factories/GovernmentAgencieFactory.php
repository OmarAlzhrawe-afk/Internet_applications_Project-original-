<?php

namespace Database\Factories;

use App\Models\GovernmentAgencie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class GovernmentAgencieFactory extends Factory
{
    protected $model = GovernmentAgencie::class;

    public function definition()
    {

        return [
            'name' => fake()->name,
            'description' => fake()->text(),
            'address' => fake()->address(),
            'contact_email' => fake()->email(),
            'contact_phone' => fake()->phoneNumber()
        ];
    }
}
