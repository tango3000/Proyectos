<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Gestor de Tareas') }} - Laravel 11</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- SortableJS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <style>
        .drag-handle { cursor: grab; }
        .drag-handle:active { cursor: grabbing; }
        .sortable-ghost { opacity: 0.4; background-color: #f3f4f6; border: 2px dashed #6366f1 !important; }
        .sortable-chosen { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col font-sans">

    <!-- Barra de Navegación -->
    <nav class="bg-indigo-700 text-white shadow-md">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('tasks.index') }}" class="flex items-center space-x-2 text-xl font-bold tracking-wide hover:opacity-90">
                <i class="fa-solid fa-list-check text-amber-300 text-2xl"></i>
                <span>TaskFlow <span class="text-xs bg-indigo-900 text-indigo-200 px-2 py-0.5 rounded-full">Laravel 11 &bull; PHP 8.3</span></span>
            </a>
            <div class="flex items-center space-x-3 text-sm">
                <span class="bg-indigo-600 px-3 py-1 rounded-full text-indigo-100 flex items-center gap-2">
                    <i class="fa-solid fa-database text-xs text-emerald-400"></i> MySQL Conectado
                </span>
            </div>
        </div>
    </nav>

    <!-- Notificaciones Flash de Blade -->
    <main class="max-w-6xl mx-auto px-4 py-8 flex-1 w-full">
        @if(session('success'))
            <div id="flash-message" class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded shadow-sm">
                <div class="font-semibold mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-rose-500"></i> Por favor corrige los errores:
                </div>
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Notificación Toast dinámica para Drag & Drop -->
        <div id="toast" class="hidden fixed bottom-6 right-6 z-50 bg-slate-900 text-white px-5 py-3 rounded-lg shadow-xl flex items-center space-x-3 transition-all duration-300">
            <i id="toast-icon" class="fa-solid fa-check text-emerald-400"></i>
            <span id="toast-text" class="text-sm font-medium"></span>
        </div>

        @yield('content')
    </main>

    <!-- Pie de página -->
    <footer class="bg-white border-t border-slate-200 py-4 mt-auto">
        <div class="max-w-6xl mx-auto px-4 text-center text-xs text-slate-500">
            Aplicación desarrollada con <strong>Artisan</strong>, <strong>Eloquent ORM</strong> y plantillas <strong>Blade</strong> en Laravel 11 y PHP 8.3.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
