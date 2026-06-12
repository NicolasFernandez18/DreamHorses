<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalles del Cuidador - DreamHorses</title>
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

            <label for="my-drawer" class="btn btn-primary drawer-button lg:hidden m-4 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </label>

            <div class="p-6 md:p-8 max-w-5xl mx-auto space-y-6">
                <h2 class="text-3xl font-bold text-base-content mb-6">
                    Detalles del Cuidador: {{ $caretaker->name }}
                </h2>

                <x-session-alert />

                <div class="bg-base-200 p-6 rounded-lg shadow">
                    <h3 class="text-xl font-semibold mb-4"> Caballos a su cuidado</h3>

                    @if ($caretaker->horsesCaretaker->count())
                        <table class="table-auto w-full text-sm text-left text-base-content">
                            <thead class="bg-base-300 text-base-content/80 uppercase">
                                <tr>
                                    <th class="p-4">Nombre</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($caretaker->horsesCaretaker as $horse)
                                    <tr class="border-b border-base-300 hover:bg-base-300">
                                        <td class="p-4">{{ $horse->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-base-content/70">Este cuidador no tiene caballos asignados.</p>
                    @endif
                </div>

                <div class="bg-base-200 p-6 rounded-lg shadow">
                    <h3 class="text-xl font-semibold mb-4"> Reasignar Caballos</h3>

                    @can('caretakers.reassign')
                        @if ($caretaker->horsesCaretaker->count())
                            <button class="btn btn-warning font-bold"
                                onclick="document.getElementById('modal_reassign').showModal()">
                                Reasignar caballos
                            </button>

                            <dialog id="modal_reassign" class="modal">
                                <div class="modal-box">
                                    <form action="{{ route('caretakers.reassign', $caretaker->id) }}" method="POST"
                                        class="space-y-4">
                                        @csrf
                                        <h3 class="font-bold text-lg">Confirmar Reasignación</h3>
                                        <p class="py-4">Estás a punto de reasignar todos los caballos de
                                            {{ $caretaker->name }}. Selecciona el nuevo cuidador y confirma.</p>

                                        <div>
                                            <label for="new_caretaker_id" class="font-semibold">Nuevo cuidador:</label>
                                            <select name="new_caretaker_id" required class="select select-bordered w-full">
                                                <option disabled selected>Seleccione un cuidador</option>
                                                @foreach ($availableCaretakers as $c)
                                                    <option value="{{ $c->id }}">
                                                        {{ $c->name }}
                                                        @if ($c->studs->isNotEmpty())
                                                            — Studs: {{ $c->studs->pluck('name')->join(', ') }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('new_caretaker_id')" class="mt-2" />
                                        </div>

                                        <div class="modal-action justify-end">
                                            <button type="button" class="btn btn-ghost"
                                                onclick="document.getElementById('modal_reassign').close()">Cancelar</button>
                                            <button type="submit" class="btn btn-warning">Confirmar Reasignación</button>
                                        </div>
                                    </form>
                                </div>
                                <form method="dialog" class="modal-backdrop">
                                    <button>Cancelar</button>
                                </form>
                            </dialog>
                        @else
                            <p class="text-base-content/70">No hay caballos que reasignar.</p>
                        @endif
                    @else
                        <p class="text-base-content/70">No tienes permiso para reasignar caballos.</p>
                    @endcan
                </div>

                <div class="pt-4">
                    <a href="{{ route('caretakers.index') }}" class="btn btn-ghost">
                        ⬅ Volver a la lista de cuidadores
                    </a>
                </div>
            </div>
        </div>
        <x-sidebar />
    </div>
</body>

</html>
