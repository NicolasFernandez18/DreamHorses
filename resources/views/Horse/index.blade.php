<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Caballos - DreamHorses</title>
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
            <h2 class="text-2xl sm:text-3xl font-bold text-base-content mb-6 flex items-center gap-2">
                Listado de Caballos
            </h2>

            <x-session-alert />
            @can('horses.create')
                <div class="mb-4">
                    <a href="{{ route('CreateHorse') }}"
                        class="btn btn-success font-bold shadow-sm">Crear
                        caballo</a>
                </div>
            @endcan
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($horses as $horse)
                    <div
                        class="card bg-base-200 shadow-xl">
                        <figure>
                            <img src="{{ $horse->photos->first() ? asset('storage/' . $horse->photos->first()->path) : ($horse->photo_path ? asset('storage/' . $horse->photo_path) : asset('images/default.png')) }}"
                                class="w-full h-48 object-cover" />
                        </figure>
                        <div class="card-body">
                            <h2 class="card-title text-primary">{{ $horse->name }}
                            </h2>
                            <p>Cuidador: <span
                                    class="text-base-content">{{ $horse->caretaker->name ?? 'Sin cuidador' }}</span>
                            </p>
                        </div>
                        <div class="card-actions justify-end p-4">
                            <a href="{{ route('horses.show', $horse->id) }}"
                                class="btn btn-sm btn-primary">
                                Ver información
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $horses->links() }}
            </div>
        </div>
    </div>

    <x-sidebar />
</div>
</body>

</html>
