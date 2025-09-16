<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="School Management API",
 *     version="1.0.0",
 *     description="API for managing school operations including students, teachers, courses, grades, and attendance",
 *     @OA\Contact(
 *         email="admin@school.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Development server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * 
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@school.com"),
 *     @OA\Property(property="role", type="string", enum={"admin","teacher","student"}, example="student"),
 *     @OA\Property(property="phone", type="string", example="+1234567890"),
 *     @OA\Property(property="address", type="string", example="123 Main St"),
 *     @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01"),
 *     @OA\Property(property="profile_image", type="string", example="profile.jpg"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Student",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="student_id", type="string", example="STU001"),
 *     @OA\Property(property="grade_level", type="string", example="10th Grade"),
 *     @OA\Property(property="enrollment_date", type="string", format="date", example="2024-01-01"),
 *     @OA\Property(property="parent_name", type="string", example="Jane Doe"),
 *     @OA\Property(property="parent_phone", type="string", example="+1234567890"),
 *     @OA\Property(property="parent_email", type="string", format="email", example="parent@email.com"),
 *     @OA\Property(property="emergency_contact", type="string", example="+0987654321"),
 *     @OA\Property(property="medical_info", type="string", example="No known allergies"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Teacher",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="employee_id", type="string", example="EMP001"),
 *     @OA\Property(property="department", type="string", example="Mathematics"),
 *     @OA\Property(property="hire_date", type="string", format="date", example="2020-01-01"),
 *     @OA\Property(property="salary", type="number", format="float", example=50000.00),
 *     @OA\Property(property="qualification", type="string", example="Master's in Mathematics"),
 *     @OA\Property(property="specialization", type="string", example="Algebra"),
 *     @OA\Property(property="office_location", type="string", example="Room 201"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Course",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Mathematics"),
 *     @OA\Property(property="code", type="string", example="MATH101"),
 *     @OA\Property(property="description", type="string", example="Basic mathematics course"),
 *     @OA\Property(property="credits", type="integer", example=3),
 *     @OA\Property(property="teacher_id", type="integer", example=1),
 *     @OA\Property(property="grade_level", type="string", example="10th Grade"),
 *     @OA\Property(property="schedule", type="string", example="Mon, Wed, Fri 9:00-10:00"),
 *     @OA\Property(property="room", type="string", example="Room 101"),
 *     @OA\Property(property="max_students", type="integer", example=30),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="teacher", ref="#/components/schemas/Teacher"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Enrollment",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="student_id", type="integer", example=1),
 *     @OA\Property(property="course_id", type="integer", example=1),
 *     @OA\Property(property="enrollment_date", type="string", format="date", example="2024-01-01"),
 *     @OA\Property(property="status", type="string", enum={"active","completed","dropped","pending"}, example="active"),
 *     @OA\Property(property="notes", type="string", example="Regular enrollment"),
 *     @OA\Property(property="student", ref="#/components/schemas/Student"),
 *     @OA\Property(property="course", ref="#/components/schemas/Course"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Grade",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="student_id", type="integer", example=1),
 *     @OA\Property(property="course_id", type="integer", example=1),
 *     @OA\Property(property="teacher_id", type="integer", example=1),
 *     @OA\Property(property="assignment_name", type="string", example="Homework 1"),
 *     @OA\Property(property="grade_value", type="number", format="float", example=85.5),
 *     @OA\Property(property="max_grade", type="number", format="float", example=100.0),
 *     @OA\Property(property="grade_type", type="string", enum={"homework","quiz","exam","project","participation"}, example="homework"),
 *     @OA\Property(property="date_given", type="string", format="date", example="2024-01-15"),
 *     @OA\Property(property="comments", type="string", example="Good work"),
 *     @OA\Property(property="percentage", type="number", format="float", example=85.5),
 *     @OA\Property(property="student", ref="#/components/schemas/Student"),
 *     @OA\Property(property="course", ref="#/components/schemas/Course"),
 *     @OA\Property(property="teacher", ref="#/components/schemas/Teacher"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Attendance",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="student_id", type="integer", example=1),
 *     @OA\Property(property="course_id", type="integer", example=1),
 *     @OA\Property(property="teacher_id", type="integer", example=1),
 *     @OA\Property(property="date", type="string", format="date", example="2024-01-15"),
 *     @OA\Property(property="status", type="string", enum={"present","absent","late","excused"}, example="present"),
 *     @OA\Property(property="notes", type="string", example="On time"),
 *     @OA\Property(property="student", ref="#/components/schemas/Student"),
 *     @OA\Property(property="course", ref="#/components/schemas/Course"),
 *     @OA\Property(property="teacher", ref="#/components/schemas/Teacher"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}