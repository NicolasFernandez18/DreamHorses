<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ver Caballo - DreamHorses</title>
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
        <div class="p-6 md:p-8 max-w-4xl mx-auto space-y-6">

            <x-session-alert />

            <div class="card bg-base-200 shadow-xl p-6">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="md:w-1/2">
                        @if ($horse->photos->count())
                            <div class="carousel w-full rounded-lg overflow-hidden shadow">
                                @foreach ($horse->photos as $index => $photo)
                                    <div id="slide{{ $index }}" class="carousel-item relative w-full">
                                        <img src="{{ asset('storage/' . $photo->path) }}"
                                            class="w-full h-80 object-cover" />
                                        <div
                                            class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                                            <a href="#slide{{ $index === 0 ? $horse->photos->count() - 1 : $index - 1 }}"
                                                class="btn btn-circle btn-ghost">❮</a>
                                            <a href="#slide{{ $index === $horse->photos->count() - 1 ? 0 : $index + 1 }}"
                                                class="btn btn-circle btn-ghost">❯</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <img src="{{ $horse->photo_path ? asset('storage/' . $horse->photo_path) : asset('images/default.png') }}"
                                class="w-full h-80 object-cover rounded-lg" />
                        @endif
                    </div>

                    <div class="md:w-1/2 space-y-2">
                        <h2 class="text-2xl font-bold text-primary"> {{ $horse->name }}</h2>
                        <p><span class="text-base-content/70 font-semibold">Raza:</span> {{ $horse->breed }}
                        </p>
                        <p><span class="text-base-content/70 font-semibold">Color:</span>
                            {{ $horse->color }}</p>
                        <p><span class="text-base-content/70 font-semibold">Género:</span>
                            {{ $horse->gender === 'male' ? 'Macho' : 'Hembra' }}</p>
                        <p><span class="text-base-content/70 font-semibold">Número de Microchip:</span>
                            {{ $horse->number_microchip }}
                        </p>
                        <p><span class="text-base-content/70 font-semibold">Nacimiento:</span>
                            {{ \Carbon\Carbon::parse($horse->birth_date)->format('d/m/Y') }}</p>
                        <p><span class="text-base-content/70 font-semibold">Padre:</span>
                            {{ $horse->father_name }}</p>
                        <p><span class="text-base-content/70 font-semibold">Madre:</span>
                            {{ $horse->mother_name }}</p>
                        <p><span class="text-base-content/70 font-semibold">Cuidador:</span>
                            {{ $horse->caretaker->name ?? 'Sin cuidador' }}</p>
                            @can('race.view')
                                <a href="{{ route('race.index', ['horse_id' => $horse->id]) }}" class="btn btn-sm btn-primary">Carreras</a>
                            @endcan
                            @can('vet-visits.view')
                                <a href="{{ route('vet-visits.index', ['horse_id' => $horse->id]) }}" class="btn btn-sm btn-primary">Visitas de veterinario</a>
                            @endcan
                            @can('training.view')
                                <a href="{{ route('training.index', ['horse_id' => $horse->id]) }}" class="btn btn-sm btn-primary">Entrenamientos</a>
                            @endcan
                            @can('expenses.view')
                                <a href="{{ route('expenses.index', ['horse_id' => $horse->id]) }}"
                                    class="btn btn-sm btn-primary">Gastos</a>
                            @endcan
                            @can('blacksmiths.view')
                                <a href="{{ route('blacksmiths.index', ['horse_id' => $horse->id]) }}"
                                    class="btn btn-sm btn-primary">Herrero</a>
                            @endcan
                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('Horseindex') }}"
                    class="btn btn-sm btn-ghost">←
                    Volver</a>
                <div class="flex gap-2">
                    @can('horses.edit')
                        <a href="{{ route('horses.edit', $horse->id) }}"
                            class="btn btn-sm btn-warning"> Editar</a>
                    @endcan

                    @can('horses.delete')
                        <div>
                            <button class="btn btn-sm btn-error" onclick="document.getElementById('modal_horse_{{ $horse->id }}').showModal()">
                                Eliminar
                            </button>
                            <x-delete-modal :id="'modal_horse_' . $horse->id" :action="route('horses.destroy', $horse->id)" body="¿Estás seguro de eliminar este caballo? Esta acción es irreversible y se perderán todos los datos asociados." />
                        </div>
                    @endcan
                </div>
                
            </div>
        </div>
    </div>

    <x-sidebar />


</div>
</body>

</html>
