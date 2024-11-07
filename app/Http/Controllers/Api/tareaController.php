<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(title="Task API", version="1.0")
 */
class tareaController extends Controller
{

    /**
     * Mostrar lista de tareas
     * @OA\Get (
     *     path="/api/tasks",
     *     tags={"Lista de tareas"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="tareas",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="number", example="1"),
     *                     @OA\Property(property="title", type="string", example="Leer"),
     *                     @OA\Property(property="description", type="string", example="Leer documentación de proyecto."),
     *                     @OA\Property(property="completed", type="number", example=1),
     *                     @OA\Property(property="due_date", type="string", example="2024-11-23"),
     *                     @OA\Property(property="created_at", type="string", example="2024-11-23T00:09:16.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", example="2024-11-23T12:33:45.000000Z")
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function index()
    {
        $task = Task::all();

        $data = [
            'tareas' => $task,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    /**
     * Registrar la información de una Tarea
     * @OA\Post (
     *     path="/api/tasks",
     *     tags={"Registrar tarea"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(property="title", type="string"),
     *                      @OA\Property(property="description", type="string"),
     *                      @OA\Property(property="completed", type="boolean"),
     *                      @OA\Property(property="due_date", type="date")
     *                 ),
     *                 example={
     *                     "title":"Leer",
     *                     "description":"Leer documentación de proyecto.",
     *                     "completed":1,
     *                     "due_date":"2024-11-23",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="CREATED",
     *          @OA\JsonContent(
     *             @OA\Property(
     *                 type="object",
     *                 property="tarea",
     *                 @OA\Property(property="title", type="string", example="Leer"),
     *                 @OA\Property(property="description", type="string", example="Leer documentación de proyecto."),
     *                 @OA\Property(property="completed", type="number", example=1),
     *                 @OA\Property(property="due_date", type="string", example="2024-11-23"),
     *                 @OA\Property(property="created_at", type="string", example="2024-11-23T00:09:16.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2024-11-23T12:33:45.000000Z"),
     *                 @OA\Property(property="id", type="number", example="1")
     *             ),
     *             @OA\Property(type="object", property="status", type="number", example="200")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="UNPROCESSABLE CONTENT",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Error en la validación de los datos."),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="title",
     *                      type="array",
     *                      @OA\Items(type="string", example="The title field is required.")
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="array",
     *                      @OA\Items(type="string", example="The description field is required.")
     *                  ),
     *                  @OA\Property(
     *                      property="completed",
     *                      type="array",
     *                      @OA\Items(type="string", example="The completed field is required.")
     *                  ),
     *                  @OA\Property(
     *                      property="due_date",
     *                      type="array",
     *                      @OA\Items(type="string", example="The due date field is required.")
     *                  ),
     *              ),
     *              @OA\Property(property="status", type="number", example="400")
     *          )
     *      )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                'regex:/\S/', // Verifica que contenga al menos un carácter que no sea espacio
            ],
            'description' => 'required',
            'completed' => 'required|boolean', // Valida que sea booleano (acepta solo 0 o 1)
            'due_date' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos.',
                'errors' => $validator->errors(),
                'status' => 400,
            ];
            return response()->json($data, 400);
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'completed' => $request->completed,
            'due_date' => $request->due_date,
        ]);

        if (!$task) {
            $data = [
                'message' => 'Error al crear la tarea.',
                'status' => 500,
            ];
            return response()->json($data, 500);
        }

        $data = [
            'tarea' => $task,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    /**
     * Mostrar la información de una tarea
     * @OA\Get (
     *     path="/api/tasks/{id}",
     *     tags={"Mostrar tarea"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="object",
     *                 property="tarea",
     *                 @OA\Property(property="id", type="number", example="1"),
     *                 @OA\Property(property="title", type="string", example="Leer"),
     *                 @OA\Property(property="description", type="string", example="Leer documentación de proyecto."),
     *                 @OA\Property(property="completed", type="number", example=1),
     *                 @OA\Property(property="due_date", type="string", example="2024-11-23"),
     *                 @OA\Property(property="created_at", type="string", example="2024-11-23T00:09:16.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2024-11-23T12:33:45.000000Z")
     *             ),
     *             @OA\Property(type="object", property="status", type="number", example="200")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="NOT FOUND",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tarea no encontrada."),
     *             @OA\Property(property="status", type="number", example="404")
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        $task = Task::find($id);

        if (!$task) {
            $data = [
                'message' => 'Tarea no encontrada.',
                'status' => 404,
            ];
            return response()->json($data, 404);
        }

        $data = [
            'tarea' => $task,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    /**
     * Actualizar la información de una tarea
     * @OA\Put (
     *     path="/api/tasks/{id}",
     *     tags={"Actualizar tarea"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(property="title", type="string"),
     *                      @OA\Property(property="description", type="string"),
     *                      @OA\Property(property="completed", type="boolean"),
     *                      @OA\Property(property="due_date", type="date")
     *                 ),
     *                 example={
     *                     "title":"Leer",
     *                     "description":"Leer documentación de proyecto.",
     *                     "completed":1,
     *                     "due_date":"2024-11-23",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="CREATED",
     *          @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tarea actualizada."),
     *             @OA\Property(
     *                 type="object",
     *                 property="tarea",
     *                 @OA\Property(property="id", type="number", example="1"),
     *                 @OA\Property(property="title", type="string", example="Leer"),
     *                 @OA\Property(property="description", type="string", example="Leer documentación de proyecto."),
     *                 @OA\Property(property="completed", type="number", example=1),
     *                 @OA\Property(property="due_date", type="string", example="2024-11-23"),
     *                 @OA\Property(property="created_at", type="string", example="2024-11-23T00:09:16.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2024-11-23T12:33:45.000000Z")
     *             ),
     *             @OA\Property(type="object", property="status", type="number", example="200")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="UNPROCESSABLE CONTENT",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Error en la validación de los datos."),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="title",
     *                      type="array",
     *                      @OA\Items(type="string", example="The title field is required.")
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="array",
     *                      @OA\Items(type="string", example="The description field is required.")
     *                  ),
     *                  @OA\Property(
     *                      property="completed",
     *                      type="array",
     *                      @OA\Items(type="string", example="The completed field is required.")
     *                  ),
     *                  @OA\Property(
     *                      property="due_date",
     *                      type="array",
     *                      @OA\Items(type="string", example="The due date field is required.")
     *                  ),
     *              ),
     *              @OA\Property(property="status", type="number", example="400")
     *          )
     *      )
     * )
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            $data = [
                'message' => 'Tarea no encontrada.',
                'status' => 404,
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                'regex:/\S/', // Verifica que contenga al menos un carácter que no sea espacio
            ],
            'description' => 'required',
            'completed' => 'required|boolean', // Valida que sea booleano (acepta solo 0 o 1)
            'due_date' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos.',
                'errors' => $validator->errors(),
                'status' => 400,
            ];
            return response()->json($data, 400);
        }

        $task->title = $request->title;
        $task->description = $request->description;
        $task->completed = $request->completed;
        $task->due_date = $request->due_date;

        $task->save();

        $data = [
            'message' => 'Tarea actualizada.',
            'tarea' => $task,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    /**
     * Eliminar la información de una tarea
     * @OA\Delete (
     *     path="/api/tasks/{id}",
     *     tags={"Eliminar tarea"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Tarea eliminada."),
     *              @OA\Property(property="status", type="number", example="200"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="NOT FOUND",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Tarea no encontrada."),
     *              @OA\Property(property="status", type="number", example="404"),
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            $data = [
                'message' => 'Tarea no encontrada.',
                'status' => 404,
            ];
            return response()->json($data, 404);
        }

        $task->delete();

        $data = [
            'message' => 'Tarea eliminada.',
            'status' => 200,
        ];

        return response()->json($data, 200);
    }
}
