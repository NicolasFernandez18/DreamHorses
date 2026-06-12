<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cuidadores - DreamHorses</title>
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
    <div class="drawer-content bg-base-100 text-base-content">
        <label for="my-drawer"
            class="btn btn-primary drawer-button lg:hidden m-4 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </label>

        <div class="p-6 md:p-8 max-w-6xl mx-auto space-y-6">
            <h2 class="text-3xl font-bold text-base-content mb-4"> Lista de Cuidadores</h2>

            <x-session-alert />

            <div class="overflow-x-auto bg-base-200 rounded-lg shadow-lg">
                <table class="table-auto w-full text-sm text-left text-base-content">
                    <thead class="bg-base-300 text-base-content/80 uppercase">
                        <tr>
                            <th class="p-4">Nombre</th>
                            <th class="p-4">Teléfono</th>
                            <th class="p-4">Dirección</th>
                            <th class="p-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($caretakers as $caretaker)
                            <tr
                                class="border-b border-base-300 hover:bg-base-300">
                                <td class="p-4">{{ $caretaker->name }}</td>
                                <td class="p-4">{{ $caretaker->phone }}</td>
                                <td class="p-4">{{ $caretaker->address }}</td>
                                <td class="p-4 flex gap-2">
                                    @can('caretakers.show')
                                        <a href="{{ route('caretakers.show', $caretaker->id) }}"
                                            class="btn btn-xs btn-info">
                                            Ver caballos
                                        </a>
                                    @endcan
                                </td>
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
