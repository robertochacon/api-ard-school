<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            CourseSeeder::class,
            EnrollmentSeeder::class,
            GradeSeeder::class,
            AttendanceSeeder::class,
        ]);
    }
}