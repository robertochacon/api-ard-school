<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;

/**
 * @OA\Tag(
 *     name="Docentes",
 *     description="Gestión de docentes"
 * )
 */
class TeacherController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/docentes",
     *     summary="Obtiene la lista de docentes con opciones de filtro.",
     *     tags={"Docentes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="nombre",
     *         in="query",
     *         description="Filtrar por Nombre completo del docente.",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de docentes obtenida exitosamente.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre_completo", type="string", example="Carlos Garcia"),
     *                 @OA\Property(property="telefono", type="string", example="111-222-3333"),
     *                 @OA\Property(property="correo_electronico", type="string", format="email", example="carlos.garcia@example.com"),
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
        $query = Teacher::with('user');

        if ($request->has('nombre')) {
            $searchTerm = $request->input('nombre');
            $query->whereHas('user', function ($userQuery) use ($searchTerm) {
                $userQuery->where(DB::raw('CONCAT(name, " ", last_name)'), 'like', '%' . $searchTerm . '%');
            });
        }

        $teachers = $query->get();

        return response()->json($teachers->map(function ($teacher) {
            return [
                'id' => $teacher->id,
                'nombre_completo' => $teacher->user->name . ' ' . $teacher->user->last_name,
                'telefono' => $teacher->user->phone,
                'correo_electronico' => $teacher->user->email,
            ];
        }));
    }

    /**
     * @OA\Post(
     *     path="/api/docentes",
     *     summary="Crea un nuevo docente.",
     *     tags={"Docentes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={
     *                 "name", "last_name", "email", "password", "identification_number", "gender",
     *                 "date_of_birth", "phone", "address", "department", "hire_date",
     *                 "emergency_contact_name", "emergency_contact_phone", "emergency_contact_email"
     *             },
     *             @OA\Property(property="name", type="string", example="Carlos"),
     *             @OA\Property(property="last_name", type="string", example="Garcia"),
     *             @OA\Property(property="email", type="string", format="email", example="carlos.garcia@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password"),
     *             @OA\Property(property="identification_number", type="string", example="987-654321-0"),
     *             @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="male"),
     *             @OA\Property(property="date_of_birth", type="string", format="date", example="1980-03-15"),
     *             @OA\Property(property="phone", type="string", example="111-222-3333"),
     *             @OA\Property(property="address", type="string", example="Avenida Siempre Viva 742"),
     *             @OA\Property(property="profile_image", type="string", format="binary", description="Optional: Teacher's profile image"),
     *             @OA\Property(property="department", type="string", example="Matemáticas"),
     *             @OA\Property(property="hire_date", type="string", format="date", example="2010-08-01"),
     *             @OA\Property(property="salary", type="number", format="float", example="50000.00"),
     *             @OA\Property(property="qualification", type="string", example="PhD en Matemáticas"),
     *             @OA\Property(property="specialization", type="string", example="Álgebra Lineal"),
     *             @OA\Property(property="office_location", type="string", example="Edificio A, Oficina 201"),
     *             @OA\Property(property="emergency_contact_name", type="string", example="Ana Garcia"),
     *             @OA\Property(property="emergency_contact_phone", type="string", example="555-987-6543"),
     *             @OA\Property(property="emergency_contact_email", type="string", format="email", example="ana.garcia@example.com"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Docente creado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Teacher created successfully"),
     *             @OA\Property(property="teacher", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre_completo", type="string", example="Carlos Garcia"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación."
     *     )
     * )
     */
    public function store(StoreTeacherRequest $request)
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
                'role' => 'teacher',
                'profile_image' => $request->file('profile_image') ? $request->file('profile_image')->store('profile_images', 'public') : null,
            ]);

            $teacher = Teacher::create([
                'user_id' => $user->id,
                'employee_id' => 'TEA-' . uniqid(), // Generate a unique employee ID
                'department' => $request->department,
                'hire_date' => $request->hire_date,
                'salary' => $request->salary ?? null,
                'qualification' => $request->qualification ?? null,
                'specialization' => $request->specialization ?? null,
                'office_location' => $request->office_location ?? null,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_contact_email' => $request->emergency_contact_email,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Teacher created successfully',
                'teacher' => [
                    'id' => $teacher->id,
                    'nombre_completo' => $user->name . ' ' . $user->last_name,
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error creating teacher', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/docentes/{id}",
     *     summary="Actualiza un registro de docente.",
     *     tags={"Docentes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del docente a actualizar.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Carlos"),
     *             @OA\Property(property="last_name", type="string", example="Garcia"),
     *             @OA\Property(property="email", type="string", format="email", example="carlos.garcia@example.com"),
     *             @OA\Property(property="identification_number", type="string", example="987-654321-0"),
     *             @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="male"),
     *             @OA\Property(property="date_of_birth", type="string", format="date", example="1980-03-15"),
     *             @OA\Property(property="phone", type="string", example="111-222-3333"),
     *             @OA\Property(property="address", type="string", example="Avenida Siempre Viva 742"),
     *             @OA\Property(property="profile_image", type="string", format="binary", description="Optional: Teacher's profile image"),
     *             @OA\Property(property="department", type="string", example="Matemáticas"),
     *             @OA\Property(property="hire_date", type="string", format="date", example="2010-08-01"),
     *             @OA\Property(property="salary", type="number", format="float", example="50000.00"),
     *             @OA\Property(property="qualification", type="string", example="PhD en Matemáticas"),
     *             @OA\Property(property="specialization", type="string", example="Álgebra Lineal"),
     *             @OA\Property(property="office_location", type="string", example="Edificio A, Oficina 201"),
     *             @OA\Property(property="emergency_contact_name", type="string", example="Ana Garcia"),
     *             @OA\Property(property="emergency_contact_phone", type="string", example="555-987-6543"),
     *             @OA\Property(property="emergency_contact_email", type="string", format="email", example="ana.garcia@example.com"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Docente actualizado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Teacher updated successfully"),
     *             @OA\Property(property="teacher", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre_completo", type="string", example="Carlos Garcia"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Docente no encontrado."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación."
     *     )
     * )
     */
    public function update(UpdateTeacherRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $teacher = Teacher::with('user')->find($id);

            if (! $teacher) {
                return response()->json(['message' => 'Teacher not found'], 404);
            }

            $user = $teacher->user;

            $user->update([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'identification_number' => $request->identification_number,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'phone' => $request->phone,
                'address' => $request->address,
                'profile_image' => $request->file('profile_image') ? $request->file('profile_image')->store('profile_images', 'public') : $user->profile_image,
            ]);

            $teacher->update([
                'department' => $request->department,
                'hire_date' => $request->hire_date,
                'salary' => $request->salary ?? null,
                'qualification' => $request->qualification ?? null,
                'specialization' => $request->specialization ?? null,
                'office_location' => $request->office_location ?? null,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_contact_email' => $request->emergency_contact_email,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Teacher updated successfully',
                'teacher' => [
                    'id' => $teacher->id,
                    'nombre_completo' => $user->name . ' ' . $user->last_name,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error updating teacher', 'error' => $e->getMessage()], 500);
        }
    }
}
