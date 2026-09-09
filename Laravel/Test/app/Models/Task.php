<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    // Campos permitidos para asignacion masiva
    protected $fillable = [
        'project_id',
        'name',
        'priority',
    ];

    /**
     * Una tarea pertenece a un proyecto opcional.
     * Relacion Eloquent: N a 1
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
