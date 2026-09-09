<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear Proyectos de prueba
        $p1 = Project::create([
            'name' => 'Desarrollo Web Laravel',
            'description' => 'Desarrollo de la nueva plataforma con Laravel 11'
        ]);

        $p2 = Project::create([
            'name' => 'Marketing Digital',
            'description' => 'Estrategia y campañas de lanzamiento'
        ]);

        $p3 = Project::create([
            'name' => 'Diseño UI / UX',
            'description' => 'Diseño visual y experiencia de usuario'
        ]);

        // 2. Crear Tareas de prueba con sus prioridades iniciales
        Task::create([
            'name' => 'Configurar entorno con PHP 8.3 y MySQL',
            'priority' => 1,
            'project_id' => $p1->id
        ]);

        Task::create([
            'name' => 'Diseñar estructura de base de datos con Eloquent',
            'priority' => 2,
            'project_id' => $p1->id
        ]);

        Task::create([
            'name' => 'Implementar sistema Drag & Drop en el navegador',
            'priority' => 3,
            'project_id' => $p1->id
        ]);

        Task::create([
            'name' => 'Planificar estrategia de contenido para redes sociales',
            'priority' => 4,
            'project_id' => $p2->id
        ]);

        Task::create([
            'name' => 'Diseñar mockups de alta fidelidad en Figma',
            'priority' => 5,
            'project_id' => $p3->id
        ]);
    }
}
