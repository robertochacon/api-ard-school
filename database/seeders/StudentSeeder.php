<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->get();

        foreach ($students as $index => $user) {
            $gradeLevels = ['9th Grade', '10th Grade', '11th Grade', '12th Grade'];
            $parentNames = ['Jane Doe', 'John Smith', 'Mary Johnson', 'Robert Wilson', 'Lisa Brown'];

            Student::create([
                'user_id' => $user->id,
                'student_id' => 'STU' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'grade_level' => $gradeLevels[$index % count($gradeLevels)],
                'enrollment_date' => now()->subMonths(rand(1, 12))->subDays(rand(0, 30)),
                'parent_name' => $parentNames[array_rand($parentNames)],
                'parent_phone' => '+1' . rand(2000000000, 9999999999),
                'parent_email' => 'parent' . ($index + 1) . '@email.com',
                'emergency_contact' => '+1' . rand(2000000000, 9999999999),
                'medical_info' => rand(0, 1) ? 'No known allergies' : 'Allergic to peanuts',
                'is_active' => true,
            ]);
        }
    }
}