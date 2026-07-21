<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Roles y permisos - DreamHorses</title>
    <script>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </label>

            <div class="p-6 md:p-8 max-w-7xl mx-auto space-y-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold">Roles y permisos</h1>
                        <p class="text-base-content/70">Administra qué vistas y acciones puede usar cada rol.</p>
                    </div>
                    <a href="{{ route('roles.create') }}" class="btn btn-success font-bold">
                        Nuevo rol
                    </a>
                </div>

                <x-session-alert />

                <div class="overflow-x-auto rounded-lg shadow-lg bg-base-200">
                    <table class="table-auto w-full text-sm text-left text-base-content">
                        <thead class="bg-base-300 text-base-content">
                            <tr>
                                <th class="p-4">Rol</th>
                                <th class="p-4">Usuarios</th>
                                <th class="p-4">Permisos</th>
                                <th class="p-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $role)
                                <tr class="border-b border-base-300 hover:bg-base-300">
                                    <td class="p-4 font-semibold">{{ $role->name }}</td>
                                    <td class="p-4">{{ $role->users->count() }}</td>
                                    <td class="p-4">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($role->permissions as $permission)
                                                <span class="badge badge-secondary">{{ $permission->name }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        @unless (in_array($role->name, ['admin', 'boss', 'caretaker'], true))
                                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-outline btn-warning  font-bold">
                                                Editar permisos
                                            </a>
                                        @else
                                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-outline btn-warning font-bold">
                                                Editar permisos
                                            </a>
                                            <div class="mt-2 text-xs text-base-content/60">
                                                Rol base del sistema. Solo se recomienda cambiar permisos.
                                            </div>
                                        @endunless
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-base-content/70">
                                        No hay roles registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <x-sidebar />
    </div>
</body>

</html>
