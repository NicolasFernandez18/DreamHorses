<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Visitas del Veterinario - DreamHorses</title>
        <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('theme') === 'cupcake') {
            document.documentElement.setAttribute('data-theme', 'cupcake');
        } else {
            document.documentElement.setAttribute('data-theme', 'forest');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/theme.js'])
</head>

<body>
    <div class="drawer lg:drawer-open">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content bg-base-100 text-base-content" x-data>
        <!-- Botón hamburguesa -->
        <label for="my-drawer" class="btn btn-primary drawer-button lg:hidden m-4 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </label>

        <!-- Contenido principal -->
        <div class="p-6 md:p-8 max-w-7xl mx-auto">
            <h2 class="text-2xl sm:text-3xl font-bold text-base-content mb-6">
                Visitas Veterinarias
            </h2>

            <x-session-alert />


            @can('vet-visits.create')
                <div class="mb-4">
                    <a href="{{ route('vet-visits.create') }}" class="btn btn-success font-bold shadow-sm">
                        Nueva Visita
                    </a>
                </div>
            @endcan

            <div class="p-6 md:p-1">
                @if ($horseId)
                    <a href="{{ route('horses.show', $horseId) }}" class="btn btn-sm btn-secondary mb-4">⬅ Volver al
                        caballo</a>
                @endif
            </div>

            <div class="overflow-x-auto shadow-lg rounded-lg">
                <table class="table-auto w-full text-sm text-left bg-base-200 text-base-content">
                    <thead class="bg-base-300 text-base-content">
                        <tr>
                            <th class="p-4">Caballo</th>
                            <th class="p-4">Veterinario</th>
                            <th class="p-4">Teléfono</th>
                            <th class="p-4">Fecha</th>
                            <th class="p-4">Diagnóstico</th>
                            <th class="p-4">Tratamiento</th>
                            <th class="p-4">Próxima Visita</th>
                            @canany(['vet-visits.edit', 'vet-visits.delete'])
                                <th class="p-4">Acciones</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visits as $visit)
                            <tr class="border-b border-base-300 hover:bg-base-300">
                                <td class="p-4">{{ $visit->horse->name }}</td>
                                <td class="p-4">{{ $visit->vet_name }}</td>
                                <td class="p-4">{{ $visit->vet_phone ?? '-' }}</td>
                                <td class="p-4">{{ \Carbon\Carbon::parse($visit->visit_date)->format('d/m/Y') }}</td>
                                <td class="p-4 max-w-xs break-words">{{ $visit->diagnosis }}</td>
                                <td class="p-4 max-w-xs break-words">{{ $visit->treatment }}</td>
                                <td class="p-4">{{ $visit->next_visit ? \Carbon\Carbon::parse($visit->next_visit)->format('d/m/Y') : '-' }}</td>
                                @canany(['vet-visits.edit', 'vet-visits.delete'])
                                    <td class="p-4 flex flex-col sm:flex-row gap-2">
                                        @can('vet-visits.edit')
                                            <a href="{{ route('vet-visits.edit', $visit->id) }}"
                                                class="btn btn-xs btn-warning">Editar</a>
                                        @endcan
                                        @can('vet-visits.delete')
                                            <div>
                                                <button class="btn btn-xs btn-error" onclick="document.getElementById('modal_visit_{{ $visit->id }}').showModal()">Eliminar</button>
                                                <x-delete-modal :id="'modal_visit_' . $visit->id" :action="route('vet-visits.destroy', $visit->id)" />
                                            </div>
                                        @endcan
                                    </td>
                                @endcanany
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-sidebar />


</div>
</body>

</html>
