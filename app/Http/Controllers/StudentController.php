<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Students",
 *     description="API endpoints for student management"
 * )
 */
class StudentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/students",
     *     summary="Get all students",
     *     tags={"Students"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Students retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Student"))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $students = Student::with('user')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $students
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/students",
     *     summary="Create a new student",
     *     tags={"Students"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","student_id","grade_level","enrollment_date"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="student_id", type="string", example="STU001"),
     *             @OA\Property(property="grade_level", type="string", example="10th Grade"),
     *             @OA\Property(property="enrollment_date", type="string", format="date", example="2024-01-01"),
     *             @OA\Property(property="parent_name", type="string", example="Jane Doe"),
     *             @OA\Property(property="parent_phone", type="string", example="+1234567890"),
     *             @OA\Property(property="parent_email", type="string", format="email", example="parent@email.com"),
     *             @OA\Property(property="emergency_contact", type="string", example="+0987654321"),
     *             @OA\Property(property="medical_info", type="string", example="No known allergies")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Student created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Student created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Student")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'student_id' => 'required|string|unique:students,student_id',
            'grade_level' => 'required|string',
            'enrollment_date' => 'required|date',
            'parent_name' => 'nullable|string',
            'parent_phone' => 'nullable|string',
            'parent_email' => 'nullable|email',
            'emergency_contact' => 'nullable|string',
            'medical_info' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $student = Student::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Student created successfully',
            'data' => $student->load('user')
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/students/{id}",
     *     summary="Get student by ID",
     *     tags={"Students"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Student ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Student retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/Student")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Student not found"
     *     )
     * )
     */
    public function show($id)
    {
        $student = Student::with(['user', 'enrollments.course', 'grades.course'])->find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $student
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/students/{id}",
     *     summary="Update student",
     *     tags={"Students"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Student ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="grade_level", type="string", example="11th Grade"),
     *             @OA\Property(property="parent_name", type="string", example="Jane Doe"),
     *             @OA\Property(property="parent_phone", type="string", example="+1234567890"),
     *             @OA\Property(property="parent_email", type="string", format="email", example="parent@email.com"),
     *             @OA\Property(property="emergency_contact", type="string", example="+0987654321"),
     *             @OA\Property(property="medical_info", type="string", example="No known allergies"),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Student updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Student updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Student")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Student not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'grade_level' => 'sometimes|string',
            'parent_name' => 'nullable|string',
            'parent_phone' => 'nullable|string',
            'parent_email' => 'nullable|email',
            'emergency_contact' => 'nullable|string',
            'medical_info' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $student->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Student updated successfully',
            'data' => $student->load('user')
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/students/{id}",
     *     summary="Delete student",
     *     tags={"Students"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Student ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Student deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Student deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Student not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found'
            ], 404);
        }

        $student->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Student deleted successfully'
        ]);
    }
}