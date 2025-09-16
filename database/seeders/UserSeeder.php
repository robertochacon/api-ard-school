<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1234567890',
            'address' => '123 Admin Street',
            'date_of_birth' => '1980-01-01',
            'is_active' => true,
        ]);

        // Teacher users
        User::create([
            'name' => 'John Smith',
            'email' => 'john.teacher@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '+1234567891',
            'address' => '456 Teacher Avenue',
            'date_of_birth' => '1985-05-15',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah.teacher@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '+1234567892',
            'address' => '789 Educator Road',
            'date_of_birth' => '1982-08-20',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Michael Brown',
            'email' => 'michael.teacher@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '+1234567893',
            'address' => '321 Professor Lane',
            'date_of_birth' => '1978-12-10',
            'is_active' => true,
        ]);

        // Student users
        User::create([
            'name' => 'Alice Student',
            'email' => 'alice.student@school.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1234567894',
            'address' => '654 Student Street',
            'date_of_birth' => '2005-03-25',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob.student@school.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1234567895',
            'address' => '987 Learner Boulevard',
            'date_of_birth' => '2005-07-12',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Carol Davis',
            'email' => 'carol.student@school.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1234567896',
            'address' => '147 Scholar Avenue',
            'date_of_birth' => '2004-11-08',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'David Miller',
            'email' => 'david.student@school.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1234567897',
            'address' => '258 Graduate Road',
            'date_of_birth' => '2005-01-30',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Emma Garcia',
            'email' => 'emma.student@school.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1234567898',
            'address' => '369 Academic Street',
            'date_of_birth' => '2004-09-14',
            'is_active' => true,
        ]);
    }
}