<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Teacher;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = Teacher::all();
        $courses = [
            [
                'name' => 'Algebra I',
                'code' => 'MATH101',
                'description' => 'Introduction to algebraic concepts and problem solving',
                'credits' => 3,
                'grade_level' => '9th Grade',
                'schedule' => 'Mon, Wed, Fri 9:00-10:00',
                'room' => 'Room 101',
                'max_students' => 25,
            ],
            [
                'name' => 'Biology',
                'code' => 'BIO101',
                'description' => 'Introduction to biological sciences',
                'credits' => 4,
                'grade_level' => '10th Grade',
                'schedule' => 'Tue, Thu 10:00-11:30',
                'room' => 'Room 102',
                'max_students' => 30,
            ],
            [
                'name' => 'English Literature',
                'code' => 'ENG101',
                'description' => 'Study of classic and contemporary literature',
                'credits' => 3,
                'grade_level' => '11th Grade',
                'schedule' => 'Mon, Wed 11:00-12:00',
                'room' => 'Room 103',
                'max_students' => 20,
            ],
            [
                'name' => 'World History',
                'code' => 'HIST101',
                'description' => 'Survey of world history from ancient times to present',
                'credits' => 3,
                'grade_level' => '10th Grade',
                'schedule' => 'Tue, Thu 1:00-2:00',
                'room' => 'Room 104',
                'max_students' => 25,
            ],
            [
                'name' => 'Physical Education',
                'code' => 'PE101',
                'description' => 'Physical fitness and sports activities',
                'credits' => 2,
                'grade_level' => '9th Grade',
                'schedule' => 'Mon, Wed, Fri 2:00-3:00',
                'room' => 'Gymnasium',
                'max_students' => 35,
            ],
        ];

        foreach ($courses as $index => $courseData) {
            $teacher = $teachers->get($index % $teachers->count());
            
            Course::create([
                'name' => $courseData['name'],
                'code' => $courseData['code'],
                'description' => $courseData['description'],
                'credits' => $courseData['credits'],
                'teacher_id' => $teacher->id,
                'grade_level' => $courseData['grade_level'],
                'schedule' => $courseData['schedule'],
                'room' => $courseData['room'],
                'max_students' => $courseData['max_students'],
                'is_active' => true,
            ]);
        }
    }
}