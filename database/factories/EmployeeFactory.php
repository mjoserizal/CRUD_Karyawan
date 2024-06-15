<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition()
    {
        return [
            'employee_name' => $this->faker->name,
            'position' => $this->faker->jobTitle,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'employment_start_date' => $this->faker->date,
            'employment_end_date' => $this->faker->optional()->date,
            'active_status' => $this->faker->boolean ? '1' : '0',
        ];
    }
}
