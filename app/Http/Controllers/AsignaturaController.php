<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use App\Http\Requests\StoreAsignaturaRequest;
use App\Http\Requests\UpdateAsignaturaRequest;

/**
 * @OA\Tag(
 *     name="Asignaturas",
 *     description="Gestión de materias académicas"
 * )
 */
class AsignaturaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/asignaturas",
     *     summary="Obtiene la lista de Asignaturas.",
     *     tags={"Asignaturas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="nombre",
     *         in="query",
     *         description="Filtrar por Nombre de asignatura.",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="docente",
     *         in="query",
     *         description="Filtrar por Docente asignado a la asignatura.",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de asignaturas obtenida exitosamente.",
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
     *         response=401,
     *         description="No autorizado."
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Course::with('teacher.user');

        if ($request->has('nombre')) {
            $query->where('name', 'like', '%' . $request->input('nombre') . '%');
        }

        if ($request->has('docente')) {
            $searchTerm = $request->input('docente');
            $query->whereHas('teacher.user', function ($userQuery) use ($searchTerm) {
                $userQuery->where(DB::raw('CONCAT(name, " ", last_name)'), 'like', '%' . $searchTerm . '%');
            });
        }

        $asignaturas = $query->get();

        return response()->json($asignaturas->map(function ($asignatura) {
            return [
                'id' => $asignatura->id,
                'nombre_asignatura' => $asignatura->name,
                'docente' => $asignatura->teacher ? $asignatura->teacher->user->name . ' ' . $asignatura->teacher->user->last_name : null,
            ];
        }));
    }

    /**
     * @OA\Post(
     *     path="/api/asignaturas",
     *     summary="Crea una nueva asignatura.",
     *     tags={"Asignaturas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={
     *                 "name", "code", "credits", "teacher_id", "grade_level"
     *             },
     *             @OA\Property(property="name", type="string", example="Matemáticas"),
     *             @OA\Property(property="code", type="string", example="MAT101"),
     *             @OA\Property(property="description", type="string", example="Curso introductorio a las matemáticas."),
     *             @OA\Property(property="credits", type="integer", example=3),
     *             @OA\Property(property="teacher_id", type="integer", example=1, description="ID del docente que imparte la asignatura."),
     *             @OA\Property(property="grade_level", type="string", example="10th Grade"),
     *             @OA\Property(property="schedule", type="string", example="Lunes, Miércoles 10:00-11:30"),
     *             @OA\Property(property="room", type="string", example="Aula 205"),
     *             @OA\Property(property="max_students", type="integer", example=30),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Asignatura creada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Asignatura created successfully"),
     *             @OA\Property(property="asignatura", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre_asignatura", type="string", example="Matemáticas"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación."
     *     )
     * )
     */
    public function store(StoreAsignaturaRequest $request)
    {
        $asignatura = Course::create($request->validated());

        return response()->json([
            'message' => 'Asignatura created successfully',
            'asignatura' => [
                'id' => $asignatura->id,
                'nombre_asignatura' => $asignatura->name,
            ]
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/asignaturas/{id}",
     *     summary="Permite editar una asignatura.",
     *     tags={"Asignaturas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la asignatura a actualizar.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Matemáticas Avanzadas"),
     *             @OA\Property(property="code", type="string", example="MAT101"),
     *             @OA\Property(property="description", type="string", example="Curso avanzado de matemáticas."),
     *             @OA\Property(property="credits", type="integer", example=4),
     *             @OA\Property(property="teacher_id", type="integer", example=1, description="ID del docente que imparte la asignatura."),
     *             @OA\Property(property="grade_level", type="string", example="11th Grade"),
     *             @OA\Property(property="schedule", type="string", example="Martes, Jueves 09:00-10:30"),
     *             @OA\Property(property="room", type="string", example="Aula 301"),
     *             @OA\Property(property="max_students", type="integer", example=25),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignatura actualizada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Asignatura updated successfully"),
     *             @OA\Property(property="asignatura", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre_asignatura", type="string", example="Matemáticas Avanzadas"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Asignatura no encontrada."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación."
     *     )
     * )
     */
    public function update(UpdateAsignaturaRequest $request, $id)
    {
        $asignatura = Course::find($id);

        if (! $asignatura) {
            return response()->json(['message' => 'Asignatura not found'], 404);
        }

        $asignatura->update($request->validated());

        return response()->json([
            'message' => 'Asignatura updated successfully',
            'asignatura' => [
                'id' => $asignatura->id,
                'nombre_asignatura' => $asignatura->name,
            ]
        ]);
    }
}
