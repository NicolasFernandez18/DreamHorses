<x-guest-layout>
    <h2 class="text-2xl font-bold mb-6 text-center text-primary">Selecciona tu rol</h2>
    <p class="text-sm text-base-content/70 text-center mb-6">
        Elige el rol con el que vas a registrarte antes de completar tus datos.
    </p>

    <div class="flex flex-col gap-4">
        @forelse ($roles as $role)
            <a href="{{ route('register', ['role' => $role->value]) }}" class="btn btn-primary w-full">
                {{ $role->label }}
            </a>
        @empty
            <div class="alert alert-warning">
                No hay roles disponibles para registro.
            </div>
        @endforelse
    </div>

    <div class="text-center mt-6">
        <a class="text-sm text-primary hover:underline" href="{{ url('/') }}">
            {{ __('Volver') }}
        </a>
    </div>
</x-guest-layout>
