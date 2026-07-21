<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar rol - DreamHorses</title>
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
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold">Editar rol</h1>
                    <p class="text-base-content/70">Ajusta los permisos del rol seleccionado.</p>
                    @if ($isSystemRole)
                        <p class="mt-2 text-sm text-warning">
                            Este es un rol base del sistema. El nombre permanece fijo, pero sus permisos sí se pueden ajustar.
                        </p>
                    @endif
                </div>

                <x-session-alert />

                <div class="card bg-base-200 border border-base-300 shadow-xl">
                    <div class="card-body">
                        @include('roles._form', [
                            'action' => route('roles.update', $role),
                            'method' => 'PUT',
                            'role' => $role,
                            'permissionSections' => $permissionSections,
                            'selectedPermissions' => $selectedPermissions,
                            'isSystemRole' => $isSystemRole,
                            'submitLabel' => 'Actualizar rol',
                        ])
                    </div>
                </div>
            </div>
        </div>

        <x-sidebar />
    </div>
</body>

</html>
