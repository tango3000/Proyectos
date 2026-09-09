<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Muestra la lista de tareas con filtro de proyecto.
     */
    public function index(Request $request)
    {
        // 1. Obtenemos todos los proyectos para el menu desplegable
        $projects = Project::orderBy('name')->get();

        // 2. Leemos el proyecto seleccionado desde el parametro GET ?project_id=...
        $projectId = $request->query('project_id');

        // 3. Consultamos las tareas usando Eloquent
        $query = Task::with('project');

        if ($projectId && $projectId !== 'all') {
            if ($projectId === 'none') {
                $query->whereNull('project_id');
            } else {
                $query->where('project_id', $projectId);
            }
        }

        // 4. Ordenamos por prioridad ascendente (1 arriba, 2 abajo...)
        $tasks = $query->orderBy('priority', 'asc')->get();

        // 5. Retornamos la vista Blade con los datos
        return view('tasks.index', compact('tasks', 'projects', 'projectId'));
    }

    /**
     * Guarda una nueva tarea en la base de datos MySQL.
     */
    public function store(Request $request)
    {
        // Validacion sencilla de datos
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        // Calculamos la siguiente prioridad (al final de la lista)
        $nextPriority = (Task::max('priority') ?? 0) + 1;

        // Guardamos con Eloquent
        Task::create([
            'name' => $request->name,
            'project_id' => $request->project_id,
            'priority' => $nextPriority,
        ]);

        return redirect()->back()->with('success', 'Tarea creada correctamente.');
    }

    /**
     * Actualiza el nombre y proyecto de una tarea existente.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        // Actualizamos los datos con Eloquent
        $task->update([
            'name' => $request->name,
            'project_id' => $request->project_id,
        ]);

        return redirect()->back()->with('success', 'Tarea actualizada correctamente.');
    }

    /**
     * Elimina una tarea de la base de datos.
     */
    public function destroy(Task $task)
    {
        // Eliminamos con Eloquent
        $task->delete();

        // Reajustamos las prioridades restantes para mantener orden secuencial 1, 2, 3...
        $tasks = Task::orderBy('priority', 'asc')->get();
        foreach ($tasks as $index => $t) {
            $t->update(['priority' => $index + 1]);
        }

        return redirect()->back()->with('success', 'Tarea eliminada correctamente.');
    }

    /**
     * Actualiza la prioridad de las tareas al arrastrar y soltar (Drag & Drop).
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:tasks,id',
        ]);

        // Recorremos el array con el nuevo orden enviado desde JavaScript
        // $index empieza en 0, por lo que la prioridad sera ($index + 1):
        // posicion 0 -> prioridad #1 (arriba)
        // posicion 1 -> prioridad #2
        // etc.
        foreach ($request->order as $index => $taskId) {
            Task::where('id', $taskId)->update([
                'priority' => $index + 1
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Prioridades actualizadas con exito.'
        ]);
    }
}
