<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;
use App\Models\Enrollment;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enrollments = Enrollment::with(['student', 'course.teacher'])->get();
        $assignmentTypes = ['homework', 'quiz', 'exam', 'project', 'participation'];
        $assignmentNames = [
            'homework' => ['Homework 1', 'Homework 2', 'Homework 3', 'Practice Problems'],
            'quiz' => ['Quiz 1', 'Quiz 2', 'Midterm Quiz', 'Chapter Quiz'],
            'exam' => ['Midterm Exam', 'Final Exam', 'Unit Test'],
            'project' => ['Research Project', 'Group Project', 'Presentation'],
            'participation' => ['Class Participation', 'Discussion Board', 'Lab Participation']
        ];

        foreach ($enrollments as $enrollment) {
            // Generate 3-8 grades per enrollment
            $numGrades = rand(3, 8);
            
            for ($i = 0; $i < $numGrades; $i++) {
                $type = $assignmentTypes[array_rand($assignmentTypes)];
                $assignmentName = $assignmentNames[$type][array_rand($assignmentNames[$type])];
                
                Grade::create([
                    'student_id' => $enrollment->student_id,
                    'course_id' => $enrollment->course_id,
                    'teacher_id' => $enrollment->course->teacher_id,
                    'assignment_name' => $assignmentName,
                    'grade_value' => rand(60, 100),
                    'max_grade' => 100,
                    'grade_type' => $type,
                    'date_given' => now()->subDays(rand(1, 90)),
                    'comments' => rand(0, 1) ? 'Good work!' : 'Keep it up!',
                ]);
            }
        }
    }
}