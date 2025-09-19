<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Course;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Attendance;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use App\Http\Requests\StoreSesionRequest;
use App\Http\Requests\UpdateSesionRequest;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Sesiones",
 *     description="Gestión de grupos de clase o periodos lectivos"
 * )
 */
class SesionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/sesiones",
     *     summary="Obtiene la lista de Sesiones.",
     *     tags={"Sesiones"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="nombre",
     *         in="query",
     *         description="Filtrar por Nombre de sesión.",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="docente",
     *         in="query",
     *         description="Filtrar por Docente principal de la sesión.",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="ano",
     *         in="query",
     *         description="Filtrar por Año de la sesión.",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de sesiones obtenida exitosamente.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre_sesion", type="string", example="Sesión 2023-2024"),
     *                 @OA\Property(property="docente", type="string", example="Carlos Garcia"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado."
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Session::with('teacher.user');

        if ($request->has('nombre')) {
            $query->where('name', 'like', '%' . $request->input('nombre') . '%');
        }

        if ($request->has('docente')) {
            $searchTerm = $request->input('docente');
            $query->whereHas('teacher.user', function ($userQuery) use ($searchTerm) {
                $userQuery->where(DB::raw('CONCAT(name, " ", last_name)'), 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('ano')) {
            $query->where('year', $request->input('ano'));
        }

        $sesiones = $query->get();

        return response()->json($sesiones->map(function ($sesion) {
            return [
                'id' => $sesion->id,
                'nombre_sesion' => $sesion->name,
                'docente' => $sesion->teacher ? $sesion->teacher->user->name . ' ' . $sesion->teacher->user->last_name : null,
            ];
        }));
    }

    /**
     * @OA\Post(
     *     path="/api/sesiones",
     *     summary="Crea una nueva sesión.",
     *     tags={"Sesiones"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={
     *                 "name", "year", "start_date", "end_date"
     *             },
     *             @OA\Property(property="name", type="string", example="Sesión 2023-2024"),
     *             @OA\Property(property="year", type="integer", example=2023),
     *             @OA\Property(property="teacher_id", type="integer", example=1, description="ID del docente principal de la sesión."),
     *             @OA\Property(property="start_date", type="string", format="date", example="2023-09-01"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2024-06-30"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sesión creada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Session created successfully"),
     *             @OA\Property(property="sesion", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre_sesion", type="string", example="Sesión 2023-2024"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación."
     *     )
     * )
     */
    public function store(StoreSesionRequest $request)
    {
        $sesion = Session::create($request->validated());

        return response()->json([
            'message' => 'Session created successfully',
            'sesion' => [
                'id' => $sesion->id,
                'nombre_sesion' => $sesion->name,
            ]
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/sesiones/{id}",
     *     summary="Actualiza la información general de la sesión.",
     *     tags={"Sesiones"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la sesión a actualizar.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Sesión 2024-2025"),
     *             @OA\Property(property="year", type="integer", example=2024),
     *             @OA\Property(property="teacher_id", type="integer", example=1, description="ID del docente principal de la sesión."),
     *             @OA\Property(property="start_date", type="string", format="date", example="2024-09-01"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2025-06-30"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sesión actualizada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Session updated successfully"),
     *             @OA\Property(property="sesion", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre_sesion", type="string", example="Sesión 2024-2025"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sesión no encontrada."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación."
     *     )
     * )
     */
    public function update(UpdateSesionRequest $request, $id)
    {
        $sesion = Session::find($id);

        if (! $sesion) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        $sesion->update($request->validated());

        return response()->json([
            'message' => 'Session updated successfully',
            'sesion' => [
                'id' => $sesion->id,
                'nombre_sesion' => $sesion->name,
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/sesiones/{id}/asignaturas",
     *     summary="Lista las asignaturas asociadas a la sesión.",
     *     tags={"Sesiones"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la sesión.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignaturas de la sesión obtenidas exitosamente.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre_asignatura", type="string", example="Matemáticas"),
     *                 @OA\Property(property="docente", type="string", example="Carlos Garcia"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sesión no encontrada."
     *     )
     * )
     */
    public function getAsignaturas(Request $request, $id)
    {
        $sesion = Session::find($id);

        if (! $sesion) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        $asignaturas = $sesion->courses()->with('teacher.user')->get();

        return response()->json($asignaturas->map(function ($asignatura) {
            return [
                'id' => $asignatura->id,
                'nombre_asignatura' => $asignatura->name,
                'docente' => $asignatura->teacher ? $asignatura->teacher->user->name . ' ' . $asignatura->teacher->user->last_name : null,
            ];
        }));
    }

    /**
     * @OA\Put(
     *     path="/api/sesiones/{id}/asignaturas",
     *     summary="Permite agregar/eliminar asignaturas de la sesión.",
     *     tags={"Sesiones"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la sesión.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={
     *                 "asignaturas"
     *             },
     *             @OA\Property(property="asignaturas", type="array",
     *                 @OA\Items(type="integer", example=1, description="ID de la asignatura.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignaturas de la sesión actualizadas exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Session assignments updated successfully"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sesión no encontrada."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación."
     *     )
     * )
     */
    public function updateAsignaturas(Request $request, $id)
    {
        $sesion = Session::find($id);

        if (! $sesion) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        $request->validate([
            'asignaturas' => 'required|array',
            'asignaturas.*' => 'exists:courses,id',
        ]);

        // Sync enrollments for this session based on provided courses
        // This assumes that adding/removing an asignatura from a session means
        // adding/removing enrollments for all students currently in the session for that asignatura.
        // This logic might need to be refined based on exact business rules.
        $currentStudentIds = $sesion->enrollments()->pluck('student_id')->unique()->toArray();
        $newCourseIds = $request->input('asignaturas');

        DB::beginTransaction();
        try {
            // Remove enrollments for courses not in the new list
            $sesion->enrollments()->whereNotIn('course_id', $newCourseIds)->delete();

            // Add enrollments for new courses for existing students in the session
            foreach ($newCourseIds as $courseId) {
                foreach ($currentStudentIds as $studentId) {
                    Enrollment::firstOrCreate(
                        ['session_id' => $sesion->id, 'student_id' => $studentId, 'course_id' => $courseId],
                        ['enrollment_date' => now(), 'status' => 'active']
                    );
                }
            }
            DB::commit();
            return response()->json(['message' => 'Session assignments updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error updating session assignments', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/sesiones/{id}/estudiantes",
     *     summary="Lista los estudiantes matriculados en la sesión.",
     *     tags={"Sesiones"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la sesión.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estudiantes de la sesión obtenidos exitosamente.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre_completo", type="string", example="Juan Perez"),
     *                 @OA\Property(property="fecha_de_nacimiento", type="string", format="date", example="2000-01-01"),
     *                 @OA\Property(property="padre_madre_o_tutor", type="string", example="Maria Lopez"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sesión no encontrada."
     *     )
     * )
     */
    public function getEstudiantes(Request $request, $id)
    {
        $sesion = Session::find($id);

        if (! $sesion) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        $students = $sesion->students()->with('user')->get();

        return response()->json($students->map(function ($student) {
            return [
                'id' => $student->id,
                'nombre_completo' => $student->user->name . ' ' . $student->user->last_name,
                'fecha_de_nacimiento' => $student->user->date_of_birth,
                'padre_madre_o_tutor' => $student->parent_name, // Assuming parent_name stores this
            ];
        }));
    }

    /**
     * @OA\Put(
     *     path="/api/sesiones/{id}/estudiantes",
     *     summary="Permite agregar/eliminar estudiantes de la sesión.",
     *     tags={"Sesiones"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la sesión.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={
     *                 "estudiantes"
     *             },
     *             @OA\Property(property="estudiantes", type="array",
     *                 @OA\Items(type="integer", example=1, description="ID del estudiante.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estudiantes de la sesión actualizados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Session students updated successfully"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sesión no encontrada."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación."
     *     )
     * )
     */
    public function updateEstudiantes(Request $request, $id)
    {
        $sesion = Session::find($id);

        if (! $sesion) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        $request->validate([
            'estudiantes' => 'required|array',
            'estudiantes.*' => 'exists:students,id',
        ]);

        $newStudentIds = $request->input('estudiantes');
        $currentCourseIds = $sesion->courses()->pluck('id')->unique()->toArray();

        DB::beginTransaction();
        try {
            // Remove enrollments for students not in the new list
            $sesion->enrollments()->whereNotIn('student_id', $newStudentIds)->delete();

            // Add enrollments for new students for existing courses in the session
            foreach ($newStudentIds as $studentId) {
                foreach ($currentCourseIds as $courseId) {
                    Enrollment::firstOrCreate(
                        ['session_id' => $sesion->id, 'student_id' => $studentId, 'course_id' => $courseId],
                        ['enrollment_date' => now(), 'status' => 'active']
                    );
                }
            }
            DB::commit();
            return response()->json(['message' => 'Session students updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error updating session students', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/sesiones/{id}/calificaciones",
     *     summary="Obtiene las calificaciones por estudiante y asignatura para la sesión.",
     *     tags={"Sesiones"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la sesión.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Calificaciones de la sesión obtenidas exitosamente.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="student_id", type="integer", example=1),
     *                 @OA\Property(property="student_name", type="string", example="Juan Perez"),
     *                 @OA\Property(property="course_id", type="integer", example=101),
     *                 @OA\Property(property="course_name", type="string", example="Matemáticas"),
     *                 @OA\Property(property="grades", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="assignment_name", type="string", example="Examen Final"),
     *                         @OA\Property(property="grade_value", type="number", format="float", example=95.5),
     *                         @OA\Property(property="max_grade", type="number", format="float", example=100.0),
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sesión no encontrada."
     *     )
     * )
     */
    public function getCalificaciones(Request $request, $id)
    {
        $sesion = Session::find($id);

        if (! $sesion) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        $grades = Grade::where('session_id', $id)
                        ->with('student.user', 'course')
                        ->get()
                        ->groupBy(['student_id', 'course_id']);

        $formattedGrades = [];
        foreach ($grades as $studentId => $studentGrades) {
            foreach ($studentGrades as $courseId => $courseGrades) {
                $student = $courseGrades->first()->student;
                $course = $courseGrades->first()->course;

                $formattedGrades[] = [
                    'student_id' => $studentId,
                    'student_name' => $student->user->name . ' ' . $student->user->last_name,
                    'course_id' => $courseId,
                    'course_name' => $course->name,
                    'grades' => $courseGrades->map(function ($grade) {
                        return [
                            'assignment_name' => $grade->assignment_name,
                            'grade_value' => $grade->grade_value,
                            'max_grade' => $grade->max_grade,
                        ];
                    })
                ];
            }
        }

        return response()->json($formattedGrades);
    }

    /**
     * @OA\Put(
     *     path="/api/sesiones/{id}/calificaciones",
     *     summary="Permite guardar las notas para el estudiante en la sesión.",
     *     tags={"Sesiones"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la sesión.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={
     *                 "student_id", "course_id", "grades"
     *             },
     *             @OA\Property(property="student_id", type="integer", example=1, description="ID del estudiante."),
     *             @OA\Property(property="course_id", type="integer", example=101, description="ID de la asignatura."),
     *             @OA\Property(property="grades", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="assignment_name", type="string", example="Matematica"),
     *                     @OA\Property(property="grade_value", type="number", format="float", example=90.0),
     *                     @OA\Property(property="max_grade", type="number", format="float", example=100.0),
     *                     @OA\Property(property="grade_type", type="string", enum={"homework", "quiz", "exam", "project", "participation"}, example="exam"),
     *                     @OA\Property(property="date_given", type="string", format="date", example="2024-05-20"),
     *                     @OA\Property(property="comments", type="string", example="Buen desempeño."),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Calificaciones actualizadas exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Grades updated successfully"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sesión, estudiante o asignatura no encontrada."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación."
     *     )
     * )
     */
    public function updateCalificaciones(Request $request, $id)
    {
        $sesion = Session::find($id);

        if (! $sesion) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'grades' => 'required|array',
            'grades.*.assignment_name' => 'required|string|max:255',
            'grades.*.grade_value' => 'required|numeric|min:0',
            'grades.*.max_grade' => 'required|numeric|min:0|gt:grades.*.grade_value',
            'grades.*.grade_type' => 'required|in:homework,quiz,exam,project,participation',
            'grades.*.date_given' => 'required|date',
            'grades.*.comments' => 'nullable|string',
        ]);

        $studentId = $request->input('student_id');
        $courseId = $request->input('course_id');
        $gradesData = $request->input('grades');

        // Ensure the student is enrolled in the session and course
        $enrollment = Enrollment::where('session_id', $id)
                                ->where('student_id', $studentId)
                                ->where('course_id', $courseId)
                                ->first();

        if (! $enrollment) {
            return response()->json(['message' => 'Student not enrolled in this course for this session'], 404);
        }

        DB::beginTransaction();
        try {
            foreach ($gradesData as $gradeData) {
                Grade::updateOrCreate(
                    [
                        'session_id' => $id,
                        'student_id' => $studentId,
                        'course_id' => $courseId,
                        'assignment_name' => $gradeData['assignment_name'],
                    ],
                    [
                        'teacher_id' => $sesion->teacher_id, // Assuming session teacher is the one giving grades
                        'grade_value' => $gradeData['grade_value'],
                        'max_grade' => $gradeData['max_grade'],
                        'grade_type' => $gradeData['grade_type'],
                        'date_given' => $gradeData['date_given'],
                        'comments' => $gradeData['comments'] ?? null,
                    ]
                );
            }
            DB::commit();
            return response()->json(['message' => 'Grades updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error updating grades', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/sesiones/{id}/asistencia",
     *     summary="Obtiene el registro de asistencia para la sesión.",
     *     tags={"Sesiones"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la sesión.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="month",
     *         in="query",
     *         description="Filtrar por mes (1-12).",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             minimum=1,
     *             maximum=12
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="day",
     *         in="query",
     *         description="Filtrar por día (1-31).",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             minimum=1,
     *             maximum=31
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="course_id",
     *         in="query",
     *         description="Filtrar por ID de asignatura.",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro de asistencia obtenido exitosamente.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="student_id", type="integer", example=1),
     *                 @OA\Property(property="student_name", type="string", example="Juan Perez"),
     *                 @OA\Property(property="course_name", type="string", example="Matemáticas"),
     *                 @OA\Property(property="date", type="string", format="date", example="2024-05-20"),
     *                 @OA\Property(property="status", type="string", enum={"present", "absent", "late", "excused"}, example="present"),
     *                 @OA\Property(property="notes", type="string", example="Llegó tarde."),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sesión no encontrada."
     *     )
     * )
     */
    public function getAsistencia(Request $request, $id)
    {
        $sesion = Session::find($id);

        if (! $sesion) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        $query = Attendance::where('session_id', $id)
                            ->with('student.user', 'course');

        if ($request->has('month')) {
            $query->whereMonth('date', $request->input('month'));
        }

        if ($request->has('day')) {
            $query->whereDay('date', $request->input('day'));
        }

        if ($request->has('course_id')) {
            $query->where('course_id', $request->input('course_id'));
        }

        $attendanceRecords = $query->get();

        return response()->json($attendanceRecords->map(function ($record) {
            return [
                'student_id' => $record->student->id,
                'student_name' => $record->student->user->name . ' ' . $record->student->user->last_name,
                'course_name' => $record->course->name,
                'date' => $record->date,
                'status' => $record->status,
                'notes' => $record->notes,
            ];
        }));
    }

    /**
     * @OA\Post(
     *     path="/api/sesiones/{id}/asistencia",
     *     summary="Permite registrar o actualizar el estado de asistencia para la sesión.",
     *     tags={"Sesiones"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la sesión.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={
     *                 "student_id", "course_id", "date", "status"
     *             },
     *             @OA\Property(property="student_id", type="integer", example=1, description="ID del estudiante."),
     *             @OA\Property(property="course_id", type="integer", example=101, description="ID de la asignatura."),
     *             @OA\Property(property="date", type="string", format="date", example="2024-05-20"),
     *             @OA\Property(property="status", type="string", enum={"present", "absent", "late", "excused"}, example="present"),
     *             @OA\Property(property="notes", type="string", example="Llegó tarde."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asistencia registrada/actualizada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Attendance recorded/updated successfully"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sesión, estudiante o asignatura no encontrada."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación."
     *     )
     * )
     */
    public function updateAsistencia(Request $request, $id)
    {
        $sesion = Session::find($id);

        if (! $sesion) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string',
        ]);

        $studentId = $request->input('student_id');
        $courseId = $request->input('course_id');
        $date = $request->input('date');

        // Ensure the student is enrolled in the session and course
        $enrollment = Enrollment::where('session_id', $id)
                                ->where('student_id', $studentId)
                                ->where('course_id', $courseId)
                                ->first();

        if (! $enrollment) {
            return response()->json(['message' => 'Student not enrolled in this course for this session'], 404);
        }

        Attendance::updateOrCreate(
            [
                'session_id' => $id,
                'student_id' => $studentId,
                'course_id' => $courseId,
                'date' => $date,
            ],
            [
                'teacher_id' => $sesion->teacher_id, // Assuming session teacher is the one recording attendance
                'status' => $request->input('status'),
                'notes' => $request->input('notes') ?? null,
            ]
        );

        return response()->json(['message' => 'Attendance recorded/updated successfully']);
    }
}
