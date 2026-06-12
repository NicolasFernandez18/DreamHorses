<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ver Stud - DreamHorses</title>
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
            <label for="my-drawer" class="btn btn-primary drawer-button lg:hidden m-4 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </label>

            <!-- Contenido principal -->
            <div class="p-6 md:p-8 max-w-4xl mx-auto space-y-6">

                <x-session-alert />

                <div class="card bg-base-200 shadow-xl p-6">
                    <h1 class="text-3xl font-bold text-primary mb-4">{{ $stud->name }}</h1>

                    <div class="space-y-2">
                        <p><span class="text-base-content/70 font-semibold">Dirección:</span> {{ $stud->address }}</p>
                        <p><span class="text-base-content/70 font-semibold">Teléfono:</span> {{ $stud->phone }}</p>
                        <p><span class="text-base-content/70 font-semibold">Propietario:</span> {{ $stud->owner->name }}
                        </p>
                    </div>
                </div>

                <div class="card bg-base-200 shadow-xl p-6">
                    <h2 class="text-xl font-semibold mb-4 text-base-content">Cuidadores del Stud</h2>

                    @forelse($stud->caretakers as $caretaker)
                        <div class="flex justify-between items-center bg-base-100 p-3 rounded-lg shadow-sm mb-2">
                            <span>{{ $caretaker->name }}</span>

                            <div class="flex gap-2">
                                @if (auth()->id() === $stud->owner_id && $caretaker->id !== $stud->owner_id)
                                    @can('studs.kick')
                                        <button class="btn btn-xs btn-error"
                                            onclick="document.getElementById('modal_kick_{{ $caretaker->id }}').showModal()">Despedir</button>
                                        <dialog id="modal_kick_{{ $caretaker->id }}" class="modal">
                                            <div class="modal-box">
                                                <h3 class="font-bold text-lg">Confirmar Despido</h3>
                                                <p class="py-4">¿Estás seguro de que deseas despedir a
                                                    {{ $caretaker->name }} de este stud?</p>
                                                <div class="modal-action">
                                                    <form method="dialog">
                                                        <button class="btn">Cancelar</button>
                                                    </form>
                                                    <form action="{{ route('studs.kick', $stud->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="caretaker_id"
                                                            value="{{ $caretaker->id }}">
                                                        <button type="submit" class="btn btn-error">Despedir</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <form method="dialog" class="modal-backdrop">
                                                <button>close</button>
                                            </form>
                                        </dialog>
                                    @endcan
                                @elseif(auth()->id() === $caretaker->id && $caretaker->id !== $stud->owner_id)
                                    @can('studs.leave')
                                        <button class="btn btn-xs btn-warning"
                                            onclick="document.getElementById('modal_leave_{{ $stud->id }}').showModal()">Renunciar</button>
                                        <dialog id="modal_leave_{{ $stud->id }}" class="modal">
                                            <div class="modal-box">
                                                <h3 class="font-bold text-lg">Confirmar Renuncia</h3>
                                                <p class="py-4">¿Estás seguro de que deseas renunciar a este stud?</p>
                                                <div class="modal-action">
                                                    <form method="dialog">
                                                        <button class="btn">Cancelar</button>
                                                    </form>
                                                    <form action="{{ route('studs.leave', $stud->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-warning">Renunciar</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <form method="dialog" class="modal-backdrop">
                                                <button>Cancelar</button>
                                            </form>
                                        </dialog>
                                    @endcan
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-base-content/70">No hay cuidadores en este stud.</p>
                    @endforelse
                </div>

                @if(Auth::id() === $stud->owner_id && auth()->user()?->can('studs.respond'))
                    <div class="card bg-base-200 shadow-xl p-6">
                        <h2 class="text-xl font-semibold mb-4 text-base-content">Solicitudes de Contrato Pendientes</h2>

                    @forelse($stud->pendingBosses as $boss)
                        <div class="flex justify-between items-center bg-base-100 p-3 rounded-lg shadow-sm mb-2">
                            <span>El jefe <strong>{{ $boss->name }}</strong> quiere contratar tu stud.</span>
                            <div class="flex gap-2">
                                <form action="{{ route('studs.hire.respond', ['stud' => $stud->id, 'boss' => $boss->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="accepted">
                                    <button type="submit" class="btn btn-xs btn-success">Aceptar</button>
                                </form>
                                <form action="{{ route('studs.hire.respond', ['stud' => $stud->id, 'boss' => $boss->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-xs btn-error">Rechazar</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-base-content/70">No hay solicitudes de contrato pendientes.</p>
                    @endforelse
                    </div>
                @endif

                <div class="flex justify-between">
                    <a href="{{ route('studs.index') }}" class="btn btn-sm btn-ghost">← Volver</a>
                </div>
            </div>
        </div>

        <x-sidebar />
    </div>
</body>

</html>
