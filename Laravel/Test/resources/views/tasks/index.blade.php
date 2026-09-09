@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Encabezado y Barra de Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                    <span>Gestor de Tareas</span>
                    <span class="text-xs bg-indigo-100 text-indigo-700 px-2.5 py-0.5 rounded-full font-semibold">
                        {{ count($tasks) }} {{ count($tasks) === 1 ? 'tarea' : 'tareas' }}
                    </span>
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Arrastra y suelta las tareas para cambiar su prioridad automáticamente (#1 arriba, #2 abajo...).
                </p>
            </div>

            <!-- Botones de Acción -->
            <div class="flex items-center gap-2">
                <button type="button" onclick="openModal('projectModal')" class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 shadow-sm transition">
                    <i class="fa-solid fa-folder-plus text-indigo-600"></i>
                    <span>Nuevo Proyecto</span>
                </button>
                <button type="button" onclick="openModal('createTaskModal')" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm transition">
                    <i class="fa-solid fa-plus"></i>
                    <span>Nueva Tarea</span>
                </button>
            </div>
        </div>

        <!-- Filtro por Proyecto (PUNTO EXTRA) -->
        <div class="mt-6 pt-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2 flex-1 max-w-md">
                <label for="project-filter" class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-1.5 whitespace-nowrap">
                    <i class="fa-solid fa-filter text-indigo-500"></i> Filtrar por Proyecto:
                </label>
                <select id="project-filter" onchange="filterByProject(this.value)" class="w-full text-sm rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 py-1.5 px-3 bg-slate-50 font-medium">
                    <option value="all" {{ request('project_id') == 'all' || !request('project_id') ? 'selected' : '' }}>
                        📁 Todos los Proyectos
                    </option>
                    <option value="none" {{ request('project_id') === 'none' ? 'selected' : '' }}>
                        📋 Tareas sin Proyecto
                    </option>
                    @foreach($projects as $proj)
                        <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>
                            📂 {{ $proj->name }} ({{ $proj->tasks->count() }})
                        </option>
                    @endforeach
                </select>
            </div>

            @if(request('project_id') && request('project_id') !== 'all')
                <a href="{{ route('tasks.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold inline-flex items-center gap-1">
                    <i class="fa-solid fa-xmark"></i> Limpiar filtro
                </a>
            @endif
        </div>
    </div>

    <!-- Lista de Tareas con Drag & Drop -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500">
            <div class="flex items-center gap-4">
                <span class="w-8 text-center">Mover</span>
                <span class="w-20">Prioridad</span>
                <span>Tarea y Proyecto</span>
            </div>
            <div class="flex items-center gap-8">
                <span class="hidden sm:inline">Fecha</span>
                <span class="w-24 text-right">Acciones</span>
            </div>
        </div>

        @if($tasks->isEmpty())
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-slate-100 text-slate-400 mb-3">
                    <i class="fa-regular fa-clipboard text-2xl"></i>
                </div>
                <h3 class="text-base font-semibold text-slate-800">No hay tareas disponibles</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                    {{ request('project_id') ? 'No se encontraron tareas para el proyecto seleccionado.' : 'Comienza creando una nueva tarea para organizar tus actividades.' }}
                </p>
                <button type="button" onclick="openModal('createTaskModal')" class="mt-4 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                    <i class="fa-solid fa-plus"></i> Crear Tarea
                </button>
            </div>
        @else
            <ul id="task-list" class="divide-y divide-slate-100">
                @foreach($tasks as $task)
                    <li data-id="{{ $task->id }}" class="task-item px-6 py-4 flex items-center justify-between hover:bg-slate-50 transition-colors bg-white group">
                        
                        <!-- Izquierda: Handle, Prioridad y Nombre -->
                        <div class="flex items-center gap-4 flex-1 mr-4">
                            <!-- Tirador Drag & Drop -->
                            <div class="drag-handle w-8 flex justify-center text-slate-300 group-hover:text-slate-500 transition cursor-grab" title="Arrastra para cambiar la prioridad">
                                <i class="fa-solid fa-grip-vertical text-lg"></i>
                            </div>

                            <!-- Badge de Prioridad -->
                            <div class="w-20">
                                <span class="priority-badge inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200 min-w-[3.5rem]">
                                    #<span class="priority-num">{{ $task->priority }}</span>
                                </span>
                            </div>

                            <!-- Información de la Tarea -->
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-slate-900 truncate">
                                    {{ $task->name }}
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    @if($task->project)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium bg-purple-50 text-purple-700 border border-purple-200 px-2 py-0.5 rounded">
                                            <i class="fa-solid fa-folder text-[10px]"></i> {{ $task->project->name }}
                                        </span>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic">Sin proyecto</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Derecha: Marcas de tiempo y Acciones -->
                        <div class="flex items-center gap-6">
                            <!-- Marcas de tiempo -->
                            <div class="hidden sm:block text-right">
                                <div class="text-xs text-slate-600 flex items-center gap-1 justify-end">
                                    <i class="fa-regular fa-clock text-slate-400 text-[10px]"></i>
                                    <span>{{ $task->created_at ? $task->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                </div>
                                @if($task->updated_at && $task->updated_at != $task->created_at)
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        Editado: {{ $task->updated_at->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            </div>

                            <!-- Botones Editar y Eliminar -->
                            <div class="w-24 flex items-center justify-end gap-2">
                                <!-- Botón Editar -->
                                <button type="button" 
                                    onclick="openEditModal({{ $task->id }}, '{{ addslashes($task->name) }}', '{{ $task->project_id ?? '' }}')"
                                    class="text-slate-400 hover:text-indigo-600 p-1.5 rounded-lg hover:bg-slate-100 transition" 
                                    title="Editar Tarea">
                                    <i class="fa-regular fa-pen-to-square text-base"></i>
                                </button>

                                <!-- Botón Eliminar -->
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta tarea?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition" title="Eliminar Tarea">
                                        <i class="fa-regular fa-trash-can text-base"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</div>

<!-- MODAL: CREAR TAREA -->
<div id="createTaskModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-plus text-indigo-600"></i> Nueva Tarea
            </h3>
            <button onclick="closeModal('createTaskModal')" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
        </div>
        <form action="{{ route('tasks.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label for="create_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nombre de la Tarea *</label>
                <input type="text" name="name" id="create_name" required placeholder="Ej. Actualizar servidor de pruebas" class="w-full text-sm rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 p-2.5 border">
            </div>
            <div>
                <label for="create_project_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Proyecto (Opcional)</label>
                <select name="project_id" id="create_project_id" class="w-full text-sm rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 p-2.5 border bg-white">
                    <option value="">-- Sin Proyecto --</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeModal('createTaskModal')" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg">Cancelar</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm">Guardar Tarea</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EDITAR TAREA -->
<div id="editTaskModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-regular fa-pen-to-square text-indigo-600"></i> Editar Tarea
            </h3>
            <button onclick="closeModal('editTaskModal')" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
        </div>
        <form id="editTaskForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nombre de la Tarea *</label>
                <input type="text" name="name" id="edit_name" required class="w-full text-sm rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 p-2.5 border">
            </div>
            <div>
                <label for="edit_project_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Proyecto (Opcional)</label>
                <select name="project_id" id="edit_project_id" class="w-full text-sm rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 p-2.5 border bg-white">
                    <option value="">-- Sin Proyecto --</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeModal('editTaskModal')" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg">Cancelar</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm">Actualizar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: CREAR PROYECTO -->
<div id="projectModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-folder-plus text-indigo-600"></i> Nuevo Proyecto
            </h3>
            <button onclick="closeModal('projectModal')" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
        </div>
        <form action="{{ route('projects.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label for="proj_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nombre del Proyecto *</label>
                <input type="text" name="name" id="proj_name" required placeholder="Ej. Lanzamiento Campaña Q3" class="w-full text-sm rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 p-2.5 border">
            </div>
            <div>
                <label for="proj_desc" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Descripción (Opcional)</label>
                <textarea name="description" id="proj_desc" rows="3" placeholder="Detalles u objetivos del proyecto" class="w-full text-sm rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 p-2.5 border"></textarea>
            </div>
            <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeModal('projectModal')" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg">Cancelar</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm">Guardar Proyecto</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Filtro dinámico por proyecto
    function filterByProject(projectId) {
        if (!projectId || projectId === 'all') {
            window.location.href = "{{ route('tasks.index') }}";
        } else {
            window.location.href = "{{ route('tasks.index') }}?project_id=" + projectId;
        }
    }

    // Manejo de modales
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    // Modal de edición
    function openEditModal(taskId, taskName, taskProjectId) {
        const form = document.getElementById('editTaskForm');
        form.action = `/tasks/${taskId}`;
        document.getElementById('edit_name').value = taskName;
        document.getElementById('edit_project_id').value = taskProjectId;
        openModal('editTaskModal');
    }

    // Inicialización de Drag & Drop con SortableJS
    document.addEventListener('DOMContentLoaded', function () {
        const list = document.getElementById('task-list');
        if (!list) return;

        Sortable.create(list, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function () {
                // 1. Recorrer los elementos en pantalla para actualizar visualmente la prioridad (#1, #2, #3...)
                const items = list.querySelectorAll('.task-item');
                const orderIds = [];

                items.forEach((item, index) => {
                    const newPriority = index + 1;
                    const priorityNum = item.querySelector('.priority-num');
                    if (priorityNum) {
                        priorityNum.textContent = newPriority;
                    }
                    orderIds.push(item.getAttribute('data-id'));
                });

                // 2. Enviar el nuevo orden a Laravel mediante AJAX (Fetch API)
                fetch("{{ route('tasks.reorder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ order: orderIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('✓ ' + data.message, 'bg-emerald-800');
                    } else {
                        showToast('Error al guardar el nuevo orden', 'bg-rose-800');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error de conexión al reordenar', 'bg-rose-800');
                });
            }
        });
    });

    // Toast flotante para notificaciones
    function showToast(message, bgColor = 'bg-slate-900') {
        const toast = document.getElementById('toast');
        const text = document.getElementById('toast-text');
        text.textContent = message;
        toast.className = `fixed bottom-6 right-6 z-50 text-white px-5 py-3 rounded-lg shadow-xl flex items-center space-x-3 transition-all duration-300 ${bgColor}`;
        toast.classList.remove('hidden');

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }
</script>
@endpush
