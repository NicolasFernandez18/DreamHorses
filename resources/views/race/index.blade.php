<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Carreras - DreamHorses</title>
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
            <div class="mb-6">
                <h1
                    class="text-2xl sm:text-3xl font-bold text-base-content text-center sm:text-left mb-2">
                    Lista de Carreras
                </h1>
                <x-session-alert />
                @can('race.create')
                    <div class="flex justify-start">
                        <a href="{{ route('race.create') }}"
                            class="btn btn-success font-bold shadow-sm">Crear
                            Carrera</a>
                    </div>
                @endcan
                  <div class="p-6 md:p-2">
                 @if($horseId)
        <a href="{{ route('horses.show', $horseId) }}" 
           class="btn btn-sm btn-secondary mb-4">⬅ Volver al caballo</a>
    @endif
    </div>
            </div>

            <!-- Buscador -->
            <div class="mb-4">
                <form action="{{ route('race.index') }}" method="GET">
                    <div class="flex">
                        <input type="text" name="search" placeholder="Buscar por Fecha..."
                            class="input input-bordered w-full max-w-xs mr-2" value="{{ request('search') }}" />
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto rounded-lg shadow-lg">
                <table
                    class="table-auto w-full text-sm text-left bg-base-200 text-base-content">
                    <thead class="bg-base-300 text-base-content">
                        <tr>
                            <th class="p-4">Fecha de Carrera</th>
                            <th class="p-4">Caballo</th>
                            <th class="p-4">Hipódromo</th>
                            <th class="p-4">Video</th>
                            <th class="p-4">Posición</th>
                            <th class="p-4">Distancia</th>
                            <th class="p-4">Descripción de la Carrera</th>
                            <th class="p-4">Jockey</th>
                            @canany(['race.edit', 'race.delete'])
                                <th class="p-4">Acciones</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($races as $race)
                            <tr
                                class="border-b border-base-300 hover:bg-base-300">
                                <td class="p-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($race->date)->format('d/m/Y') }}</td>
                                <td class="p-4 whitespace-nowrap">{{ $race->horse->name }}</td>
                                <td class="p-4 whitespace-nowrap">{{ $race->hipodromo }}</td>
                                <td class="p-4 whitespace-nowrap">
                               @if($race->video)
                               @php
                               $embedUrl = str_replace('watch?v=', 'embed/', $race->video);
                               @endphp
                               <iframe width="200" height="100" src="{{ $embedUrl }}" frameborder="0" allowfullscreen></iframe>
                               @endif
                            </td>
                                <td class="p-4 whitespace-nowrap">{{ $race->place }}</td>
                            <td class="p-4 whitespace-nowrap">{{ $race->distance }}Mt</td>
                                <td class="p-4 max-w-xs break-words">{{ $race->description }}</td>
                                <td class="p-4 whitespace-nowrap">{{ $race->jockey }}</td>
                                @canany(['race.edit', 'race.delete'])
                                    <td class="p-4 flex flex-col sm:flex-row gap-2">
                                        @can('race.edit')
                                        <a href="{{ route('race.edit', $race->id) }}"
                                            class="btn btn-xs btn-warning">Editar</a>
                                        @endcan
                                        @can('race.delete')
                                            <div>
                                                <button class="btn btn-xs btn-error" onclick="document.getElementById('modal_race_{{ $race->id }}').showModal()">Eliminar</button>
                                                <x-delete-modal :id="'modal_race_' . $race->id" :action="route('race.destroy', $race->id)" />
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
