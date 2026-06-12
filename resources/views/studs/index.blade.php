<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Studs - DreamHorses</title>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </label>

        <!-- Contenido principal -->
        <div class="p-6 md:p-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-base-content mb-6 flex items-center gap-2">
                Lista de Studs
            </h2>

            <x-session-alert />

            @can('studs.create')
                <div class="mb-4">
                    <a href="{{ route('studs.create') }}" class="btn btn-success font-bold shadow-sm">Crear nuevo Stud</a>
                </div>
            @endcan

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($studs as $stud)
                    <div class="card bg-base-200 shadow-xl">
                        <div class="card-body">
                            <h2 class="card-title text-primary">{{ $stud->name }}</h2>
                            <p><strong>Dirección:</strong> {{ $stud->address }}</p>
                            <p><strong>Teléfono:</strong> {{ $stud->phone }}</p>
                            <p><strong>Propietario:</strong> {{ $stud->owner->name }}</p>
                            <p><strong>Precio de Contratación:</strong> ${{ number_format($stud->amount) }}</p>
                            <p><strong>Descripción:</strong> {{ $stud->description }}</p>
                         

                            <div class="card-actions justify-end mt-4 flex flex-wrap gap-2">
                                <a href="{{ route('studs.show', $stud->id) }}" class="btn btn-sm btn-info">Ver</a>

                                @canany(['studs.edit', 'studs.delete'])
                                    @if(auth()->id() === $stud->owner_id)
                                        @can('studs.edit')
                                            <a href="{{ route('studs.edit', $stud->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                        @endcan
                                        @can('studs.delete')
                                            <div>
                                                <button class="btn btn-sm btn-error" onclick="document.getElementById('modal_stud_destroy_{{ $stud->id }}').showModal()">Eliminar</button>
                                                <x-delete-modal :id="'modal_stud_destroy_' . $stud->id" :action="route('studs.destroy', $stud->id)" body="¿Estás seguro de que deseas eliminar este stud? Esta acción no se puede deshacer." />
                                            </div>
                                        @endcan
                                    @endif
                                @endcanany

                                @can('studs.join')
                                    @php
                                        $isMember = $stud->caretakers->contains('id', auth()->id());
                                    @endphp
                                    @if(!$isMember)
                                        <form action="{{ route('studs.join', $stud->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Unirse</button>
                                        </form>
                                    @endif
                                @endcan

                                @can('studs.hire')
                                    @php
                                        $contract = auth()->user()->contractedStuds()->where('stud_id', $stud->id)->first();
                                    @endphp

                                    @if(!$contract)
                                        <form action="{{ route('studs.hire', $stud->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Solicitar Contrato</button>
                                        </form>
                                    @elseif($contract->pivot->status === 'pending')
                                        <button class="btn btn-sm btn-disabled">Solicitud Pendiente</button>
                                    @elseif($contract->pivot->status === 'accepted')
                                        @can('studs.fire')
                                            <form action="{{ route('studs.fire', $stud->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-error">Cancelar Contrato</button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-disabled">Contrato Activo</button>
                                        @endcan
                                    @elseif($contract->pivot->status === 'rejected')
                                        <form action="{{ route('studs.hire', $stud->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning">Reenviar Solicitud</button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $studs->links() }}
            </div>
        </div>
    </div>

    <x-sidebar />
</div>
</body>

</html>
