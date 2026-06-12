<!-- Menú lateral -->
<div class="drawer-side">
    <label for="my-drawer" class="drawer-overlay"></label>
    <ul class="menu bg-base-200 min-h-screen w-64 p-4 flex flex-col gap-2 text-base-content">
        <!-- Sección Principal -->
        <div class="mb-2">
            <!-- Notificaciones -->
            <div class="mb-2">
                <div class="dropdown dropdown-end w-full">
                    <label tabindex="0" class="btn btn-ghost w-full justify-start gap-2 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Notificaciones
                        @if ($unreadNotifications->count())
                            <span class="badge badge-primary">{{ $unreadNotifications->count() }}</span>
                        @endif
                    </label>
                    <ul tabindex="0"
                        class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-60 z-[100] max-h-96 overflow-y-auto">
                        @forelse($unreadNotifications as $notification)
                            <li class="border-b border-base-300">
                                <a href="{{ route('notifications.markAsRead', $notification->id) }}" class="text-wrap">
                                    <small
                                        class="text-xs text-base-content/60">{{ $notification->created_at->diffForHumans() }}</small>
                                    <p class="font-semibold">{{ $notification->data['message'] }}</p>
                                </a>
                            </li>
                        @empty
                            <li class="p-4 text-center text-sm text-base-content/70">No tienes notificaciones nuevas.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <li class="mb-2">
                <a href="{{ route('dashboard') }}" class="btn btn-accent w-full justify-start gap-2 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Panel principal
                </a>
            </li>

            @can('studs.view')
                <li class="mb-2">
                    <a href="{{ route('studs.index') }}" class="btn btn-accent w-full justify-start gap-2 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Studs
                    </a>
                </li>
            @endcan
        </div>

        <div class="divider my-1"></div>

        <!-- Sección Control -->
        <div class="mb-2">
            <h3 class="text-base-content/60 text-xs font-bold uppercase tracking-wider px-4 mb-2">Control</h3>

            @can('horses.view')
                <li class="mb-1">
                    <a href="{{ route('Horseindex') }}"
                        class="btn btn-ghost w-full justify-start gap-2 hover:bg-base-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Caballos
                    </a>
                </li>
            @endcan

            @can('race.view')
                <li class="mb-1">
                    <a href="{{ route('race.index') }}"
                        class="btn btn-ghost w-full justify-start gap-2 hover:bg-base-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Carreras
                    </a>
                </li>
            @endcan

            @can('caretakers.view')
                <li class="mb-1">
                    <a href="{{ route('caretakers.index') }}"
                        class="btn btn-ghost w-full justify-start gap-2 hover:bg-base-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Cuidadores
                    </a>
                </li>
            @endcan

            @can('training.view')
                <li class="mb-1">
                    <a href="{{ route('training.index') }}"
                        class="btn btn-ghost w-full justify-start gap-2 hover:bg-base-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Entrenamientos
                    </a>
                </li>
            @endcan

            @can('blacksmiths.view')
                <li class="mb-1">
                    <a href="{{ route('blacksmiths.index') }}"
                        class="btn btn-ghost w-full justify-start gap-2 hover:bg-base-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c-.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Herrería
                    </a>
                </li>
            @endcan

            @can('vet-visits.view')
                <li class="mb-1">
                    <a href="{{ route('vet-visits.index') }}"
                        class="btn btn-ghost w-full justify-start gap-2 hover:bg-base-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Veterinario
                    </a>
                </li>
            @endcan
        </div>

        <div class="divider my-1"></div>

        <!-- Sección Gestión -->
        <div class="mb-2">
            <h3 class="text-base-content/60 text-xs font-bold uppercase tracking-wider px-4 mb-2">Gestión</h3>

            @can('calendar.view')
                <li class="mb-1">
                    <a href="{{ route('calendar.index') }}"
                        class="btn btn-ghost w-full justify-start gap-2 hover:bg-base-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Eventos
                    </a>
                </li>
            @endcan

            @can('expenses.view')
                <li class="mb-1">
                    <a href="{{ route('expenses.index') }}"
                        class="btn btn-ghost w-full justify-start gap-2 hover:bg-base-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Gastos
                    </a>
                </li>
            @endcan

            @can('expenses.chart')
                <li class="mb-1">
                    <a href="{{ route('expenses.chart') }}"
                        class="btn btn-ghost w-full justify-start gap-2 hover:bg-base-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Gráfico de Gastos
                    </a>
                </li>
            @endcan

            @can('expenses.summary')
                <li class="mb-1">
                    <a href="{{ route('expenses.summary') }}"
                        class="btn btn-ghost w-full justify-start gap-2 hover:bg-base-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Resumen de Gastos
                    </a>
                </li>
            @endcan
        </div>

        @can('roles.manage')
            <div class="divider my-1"></div>
            <div class="mb-2">
                <h3 class="text-base-content/60 text-xs font-bold uppercase tracking-wider px-4 mb-2">Administración</h3>
                <li class="mb-1">
                    <a href="{{ route('roles.index') }}"
                        class="btn btn-ghost w-full justify-start gap-2 hover:bg-base-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Roles y permisos
                    </a>
                </li>
            </div>
        @endcan

        @auth
            @php
                $user = Auth::user();
                $name = trim($user->name ?? '');
                $initials = '';
                if ($name !== '') {
                    $parts = preg_split('/\s+/', $name);
                    if (count($parts) >= 2) {
                        $first = mb_substr($parts[0], 0, 1);
                        $second = mb_substr($parts[1], 0, 1);
                        $initials = mb_strtoupper($first) . mb_strtoupper($second);
                    } else {
                        $first = mb_substr($name, 0, 1);
                        $second = mb_substr($name, 1, 1);
                        if ($second !== '') {
                            $initials = mb_strtoupper($first) . mb_strtoupper($second);
                        } else {
                            $initials = mb_strtoupper($first);
                        }
                    }
                }
                $role = method_exists($user, 'getRoleNames')
                    ? $user->getRoleNames()->first() ?? ''
                    : $user->roles->pluck('name')->first() ?? '';

                $roles = [
                    'caretaker' => 'Cuidador',
                    'boss' => 'Jefe',
                    'admin' => 'Administrador',
                ];
                $roleTranslated = $roles[$role] ?? ucfirst($role);
            @endphp

            <div class="mt-auto pt-4 border-t border-base-300">
                <div class="dropdown dropdown-top dropdown-end w-full">
                    <label tabindex="0"
                        class="btn btn-ghost w-full justify-start gap-3 h-auto py-3 px-3 hover:bg-base-300 transition-all rounded-lg">
                        <div class="avatar placeholder">
                            <div
                                class="bg-accent text-accent-content w-11 h-11 rounded-full flex items-center justify-center">
                                <span class="text-lg font-bold">{{ $initials ?: 'U' }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-start flex-1 min-w-0">
                            <span class="font-semibold text-sm truncate w-full text-left">{{ $user->name }}</span>
                            @if ($role)
                                <span class="text-xs opacity-60 truncate w-full text-left">{{ $roleTranslated }}</span>
                            @endif
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60 flex-shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </label>

                    <ul tabindex="-1"
                        class="dropdown-content menu bg-base-100 rounded-lg z-[100] w-60 p-2 shadow-xl border border-base-300 mb-2">
                        <li class="px-3 py-2 mb-1 pointer-events-none">
                            <div class="flex flex-col gap-1">
                                <span class="font-bold text-sm">{{ $user->name }}</span>
                                @if ($role)
                                    <span
                                        class="badge badge-accent badge-xs mt-1 self-center">{{ $roleTranslated }}</span>
                                @endif
                                <span class="text-xs opacity-60 truncate">{{ $user->email }}</span>
                            </div>
                        </li>

                        <div class="divider my-1"></div>

                        <li>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 py-2 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Ver perfil</span>
                            </a>
                        </li>

                        <div class="divider my-1"></div>

                        <li>
                            <a href="#"
                                class="flex items-center gap-2 py-2 text-sm text-error hover:bg-error hover:text-error-content"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Cerrar sesión</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        @endauth
    </ul>
</div>
