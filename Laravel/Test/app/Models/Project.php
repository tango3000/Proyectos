<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    // Campos que permitimos asignar masivamente
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Un proyecto tiene muchas tareas.
     * Relacion Eloquent: 1 a N
     */
    public function tasks(): HasMany
    {
        // Ordenamos las tareas por prioridad ascendente (1 arriba, 2 abajo...)
        return $this->hasMany(Task::class)->orderBy('priority', 'asc');
    }
}
