<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas de la Aplicación Web (Gestor de Tareas)
|--------------------------------------------------------------------------
| Aquí se organizan todas las rutas de forma modular, lógica y mantenible,
| aplicando buenas prácticas para principiantes:
| 1. Uso de Controladores dedicados.
| 2. Nombramiento de rutas (->name(...)).
| 3. Agrupación por funcionalidad con prefijos (prefix / group).
*/

// Redirección de la raíz '/' al listado de tareas
Route::redirect('/', '/tasks');

// ==========================================
// 1. MÓDULO DE TAREAS (TaskController)
// ==========================================
Route::prefix('tasks')->name('tasks.')->controller(TaskController::class)->group(function () {
    // Listar tareas (con filtro opcional de proyectos)
    Route::get('/', 'index')->name('index');

    // Crear una nueva tarea
    Route::post('/', 'store')->name('store');

    // Actualizar una tarea existente
    Route::put('/{task}', 'update')->name('update');

    // Eliminar una tarea
    Route::delete('/{task}', 'destroy')->name('destroy');

    // Reordenar prioridades mediante Drag & Drop (AJAX)
    Route::post('/reorder', 'reorder')->name('reorder');
});

// ==========================================
// 2. MÓDULO DE PROYECTOS (ProjectController)
// ==========================================
Route::prefix('projects')->name('projects.')->controller(ProjectController::class)->group(function () {
    // Crear un nuevo proyecto
    Route::post('/', 'store')->name('store');

    // Eliminar un proyecto
    Route::delete('/{project}', 'destroy')->name('destroy');
});

