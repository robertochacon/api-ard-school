<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\User;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = User::where('role', 'teacher')->get();

        foreach ($teachers as $index => $user) {
            $departments = ['Mathematics', 'Science', 'English', 'History', 'Physical Education'];
            $qualifications = ['Bachelor of Education', 'Master of Education', 'PhD in Education'];
            $specializations = ['Algebra', 'Biology', 'Literature', 'World History', 'Sports Science'];

            Teacher::create([
                'user_id' => $user->id,
                'employee_id' => 'EMP' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'department' => $departments[$index % count($departments)],
                'hire_date' => now()->subYears(rand(1, 10))->subMonths(rand(0, 11))->subDays(rand(0, 30)),
                'salary' => rand(40000, 80000),
                'qualification' => $qualifications[array_rand($qualifications)],
                'specialization' => $specializations[$index % count($specializations)],
                'office_location' => 'Room ' . (200 + $index),
                'is_active' => true,
            ]);
        }
    }
}