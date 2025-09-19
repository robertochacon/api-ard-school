<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;
use App\Http\Requests\StoreStudentRequest;

/**
 * @OA\Tag(
 *     name="Estudiantes",
 *     description="Gestión de estudiantes"
 * )
 */
class StudentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/estudiantes",
     *     summary="Obtiene la lista de estudiantes con opciones de filtro y visualización.",
     *     tags={"Estudiantes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="nombre",
     *         in="query",
     *         description="Filtrar por Nombre completo del estudiante.",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de estudiantes obtenida exitosamente.",
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
     *         response=401,
     *         description="No autorizado."
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Student::with('user');

        if ($request->has('nombre')) {
            $searchTerm = $request->input('nombre');
            $query->whereHas('user', function ($userQuery) use ($searchTerm) {
                $userQuery->where(DB::raw('CONCAT(name, " ", last_name)'), 'like', '%' . $searchTerm . '%');
            });
        }

        $students = $query->get();

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
     * @OA\Post(
     *     path="/api/estudiantes",
     *     summary="Crea un nuevo estudiante.",
     *     tags={"Estudiantes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={
     *                 "name", "last_name", "email", "password", "identification_number", "gender",
     *                 "date_of_birth", "phone", "address", "parent_name", "parent_phone", "parent_email",
     *                 "emergency_contact_name", "emergency_contact_phone", "emergency_contact_email"
     *             },
     *             @OA\Property(property="name", type="string", example="Juan"),
     *             @OA\Property(property="last_name", type="string", example="Perez"),
     *             @OA\Property(property="email", type="string", format="email", example="juan.perez@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password"),
     *             @OA\Property(property="identification_number", type="string", example="123-456789-0"),
     *             @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="male"),
     *             @OA\Property(property="date_of_birth", type="string", format="date", example="2005-05-10"),
     *             @OA\Property(property="phone", type="string", example="123-456-7890"),
     *             @OA\Property(property="address", type="string", example="Calle Falsa 123"),
     *             @OA\Property(property="parent_name", type="string", example="Maria Lopez"),
     *             @OA\Property(property="parent_phone", type="string", example="987-654-3210"),
     *             @OA\Property(property="parent_email", type="string", format="email", example="maria.lopez@example.com"),
     *             @OA\Property(property="emergency_contact_name", type="string", example="Pedro Lopez"),
     *             @OA\Property(property="emergency_contact_phone", type="string", example="555-123-4567"),
     *             @OA\Property(property="emergency_contact_email", type="string", format="email", example="pedro.lopez@example.com"),
     *             @OA\Property(property="profile_image", type="string", format="binary", description="Optional: Student's profile image"),
     *             @OA\Property(property="grade_level", type="string", example="10th Grade"),
     *             @OA\Property(property="enrollment_date", type="string", format="date", example="2023-09-01"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Estudiante creado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Student created successfully"),
     *             @OA\Property(property="student", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre_completo", type="string", example="Juan Perez"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación."
     *     )
     * )
     */
    public function store(StoreStudentRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'identification_number' => $request->identification_number,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'phone' => $request->phone,
                'address' => $request->address,
                'role' => 'student',
                'profile_image' => $request->file('profile_image') ? $request->file('profile_image')->store('profile_images', 'public') : null,
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'student_id' => 'STU-' . uniqid(), // Generate a unique student ID
                'grade_level' => $request->grade_level,
                'enrollment_date' => $request->enrollment_date,
                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_phone,
                'parent_email' => $request->parent_email,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_contact_email' => $request->emergency_contact_email,
                'medical_info' => $request->medical_info ?? null,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Student created successfully',
                'student' => [
                    'id' => $student->id,
                    'nombre_completo' => $user->name . ' ' . $user->last_name,
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error creating student', 'error' => $e->getMessage()], 500);
        }
    }
}
