<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Enrollment;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enrollments = Enrollment::with(['student', 'course.teacher'])->get();
        $statuses = ['present', 'absent', 'late', 'excused'];

        foreach ($enrollments as $enrollment) {
            // Generate attendance for the last 30 days
            for ($i = 0; $i < 30; $i++) {
                $date = now()->subDays($i);
                
                // Skip weekends
                if ($date->isWeekend()) {
                    continue;
                }

                // Randomly assign attendance status (80% present, 15% absent, 3% late, 2% excused)
                $rand = rand(1, 100);
                if ($rand <= 80) {
                    $status = 'present';
                } elseif ($rand <= 95) {
                    $status = 'absent';
                } elseif ($rand <= 98) {
                    $status = 'late';
                } else {
                    $status = 'excused';
                }

                Attendance::create([
                    'student_id' => $enrollment->student_id,
                    'course_id' => $enrollment->course_id,
                    'teacher_id' => $enrollment->course->teacher_id,
                    'date' => $date->format('Y-m-d'),
                    'status' => $status,
                    'notes' => $status === 'absent' ? 'Unexcused absence' : null,
                ]);
            }
        }
    }
}