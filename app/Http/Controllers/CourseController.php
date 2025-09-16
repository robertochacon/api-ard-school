<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Courses",
 *     description="API endpoints for course management"
 * )
 */
class CourseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/courses",
     *     summary="Get all courses",
     *     tags={"Courses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Courses retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Course"))
     *         )
     *     )
     * )
     */
    public function index()
    {
        $courses = Course::with('teacher.user')->get();

        return response()->json([
            'status' => 'success',
            'data' => $courses
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/courses",
     *     summary="Create a new course",
     *     tags={"Courses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","code","credits","teacher_id","grade_level"},
     *             @OA\Property(property="name", type="string", example="Mathematics"),
     *             @OA\Property(property="code", type="string", example="MATH101"),
     *             @OA\Property(property="description", type="string", example="Basic mathematics course"),
     *             @OA\Property(property="credits", type="integer", example=3),
     *             @OA\Property(property="teacher_id", type="integer", example=1),
     *             @OA\Property(property="grade_level", type="string", example="10th Grade"),
     *             @OA\Property(property="schedule", type="string", example="Mon, Wed, Fri 9:00-10:00"),
     *             @OA\Property(property="room", type="string", example="Room 101"),
     *             @OA\Property(property="max_students", type="integer", example=30)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Course created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Course created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Course")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:courses,code',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
            'teacher_id' => 'required|exists:teachers,id',
            'grade_level' => 'required|string',
            'schedule' => 'nullable|string',
            'room' => 'nullable|string',
            'max_students' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $course = Course::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Course created successfully',
            'data' => $course->load('teacher.user')
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/courses/{id}",
     *     summary="Get course by ID",
     *     tags={"Courses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Course ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/Course")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $course = Course::with(['teacher.user', 'enrollments.student.user'])->find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/courses/{id}",
     *     summary="Update course",
     *     tags={"Courses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Course ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Advanced Mathematics"),
     *             @OA\Property(property="description", type="string", example="Advanced mathematics course"),
     *             @OA\Property(property="schedule", type="string", example="Mon, Wed, Fri 10:00-11:00"),
     *             @OA\Property(property="room", type="string", example="Room 102"),
     *             @OA\Property(property="max_students", type="integer", example=25),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course updated successfully"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'schedule' => 'nullable|string',
            'room' => 'nullable|string',
            'max_students' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $course->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Course updated successfully',
            'data' => $course->load('teacher.user')
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/courses/{id}",
     *     summary="Delete course",
     *     tags={"Courses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Course ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course deleted successfully"
     *     )
     * )
     */
    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $course->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Course deleted successfully'
        ]);
    }
}