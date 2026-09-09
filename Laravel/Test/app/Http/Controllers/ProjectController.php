<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Guarda un nuevo proyecto en la base de datos MySQL.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Project::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Proyecto creado correctamente.');
    }

    /**
     * Elimina un proyecto de la base de datos.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('tasks.index')->with('success', 'Proyecto eliminado correctamente.');
    }
}
