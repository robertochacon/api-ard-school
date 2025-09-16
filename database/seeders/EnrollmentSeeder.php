<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $courses = Course::all();

        // Enroll each student in 2-4 random courses
        foreach ($students as $student) {
            $randomCourses = $courses->random(rand(2, 4));
            
            foreach ($randomCourses as $course) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'enrollment_date' => now()->subMonths(rand(1, 6))->subDays(rand(0, 30)),
                    'status' => 'active',
                    'notes' => 'Regular enrollment',
                ]);
            }
        }
    }
}