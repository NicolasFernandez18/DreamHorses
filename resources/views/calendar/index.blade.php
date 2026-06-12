<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Eventos - DreamHorses</title>
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
        <label for="my-drawer"
            class="btn btn-primary drawer-button lg:hidden m-4 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </label>

        <!-- Contenido principal -->
        <div class="p-6 md:p-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-base-content text-center sm:text-left mb-6">
                Lista de Eventos
            </h1>
            <x-session-alert />
                <div class="flex justify-start mb-4 gap-2">
                <form action="{{ route('calendarhorse') }}" method="get">
                    <button type="submit" class="btn btn-success font-bold shadow-sm">
                        Calendario
                    </button>
                </form>
                @can('calendar.create')
                    <form action="{{ route('calendar.create') }}" method="get">
                        <button type="submit" class="btn btn-success font-bold shadow-sm">
                            Crear Evento
                        </button>
                    </form>
                @endcan
                </div>
             <div class="overflow-x-auto rounded-lg shadow-lg">
                <table
                    class="table-auto w-full text-sm text-left bg-base-200 text-base-content">
                    <thead class="bg-base-300 text-base-content">
                        <tr>
                            <th class="p-4">Titulo</th>
                            <th class="p-4">Caballo</th>
                            <th class="p-4">Fecha</th>
                            <th class="p-4">Hora</th>
                            <th class="p-4">Tipo de Evento</th>
                            <th class="p-4">Descripción</th>
                            @canany(['calendar.edit', 'calendar.delete'])
                                <th class="p-4">Acciones</th>
                            @endcanany
                        </tr>

                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr
                                class="border-b border-base-300 hover:bg-base-300">
                                <td class="p-4  break-words">{{ $event->title }}</td>
                                <td class="p-4 whitespace-nowrap">{{ $event->horse->name }}</td>
                                <td class="p-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}</td>
                                <td class="p-4 whitespace-nowrap">{{ $event->event_time }}</td>
                                <td class="p-4 whitespace-nowrap">{{ $event->category }}</td>
                                <td class="p-4 max-w-xs break-words">{{ $event->description }}</td>
                                @canany(['calendar.edit', 'calendar.delete'])
                                    <td class="p-4 flex flex-col md:flex-row gap-2">
                                        @can('calendar.edit')
                                        <a href="{{ route('calendar.edit', $event) }}"
                                            class="btn btn-xs btn-warning">Editar</a>
                                        @endcan
                                        @can('calendar.delete')
                                            <div>
                                                <button class="btn btn-xs btn-error" onclick="document.getElementById('modal_event_{{ $event->id }}').showModal()">Eliminar</button>
                                                <x-delete-modal :id="'modal_event_' . $event->id" :action="route('calendar.destroy', $event)" />
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
