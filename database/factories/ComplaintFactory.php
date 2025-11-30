<?php

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\User;
use App\Models\GovernmentAgencie;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComplaintFactory extends Factory
{
    protected $model = Complaint::class;

    public function definition()
    {
        // احضر مواطن client
        $citizenId = User::where('role', 'client')->inRandomOrder()->value('id');
        if (!$citizenId) {
            $citizenId = User::factory()->create(['role' => 'client'])->id;
        }

        // احضر موظف employee
        $employeeId = User::where('role', 'employee')->inRandomOrder()->value('id');
        if (!$employeeId) {
            $employeeId = User::factory()->create(['role' => 'employee'])->id;
        }

        // احضر وكالة حكومية
        $agencyId = GovernmentAgencie::inRandomOrder()->value('id');
        if (!$agencyId) {
            $agencyId = GovernmentAgencie::factory()->create()->id;
        }

        return [
            'title'       => fake()->sentence(),
            'citizen_id'  => $citizenId,
            'employee_id' => $employeeId,
            'agency_id'   => $agencyId,
            'description' => fake()->paragraph(),
            'type'        => fake()->randomElement(['type1', 'type2', 'type3']),
            'priority'    => fake()->randomElement(['high', 'medium', 'low']),
            'status'      => fake()->randomElement(['new', 'in_review', 'in_progress', 'awaiting_info', 'resolved', 'rejected', 'closed']),
            'latitude'    => fake()->latitude(),
            'longitude'   => fake()->longitude(),
            'address_text' => fake()->address(),
            'is_locked'   => fake()->boolean(),
        ];
    }
}
