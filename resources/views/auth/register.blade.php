<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <input type="hidden" name="role" value="{{ old('role', $role ?? '') }}">

        <div class="mb-4 rounded-lg border border-primary/20 bg-primary/5 p-4">
            <p class="text-sm text-base-content/70">Rol seleccionado</p>
            <p class="text-lg font-semibold text-primary">{{ $roleLabel ?? 'Sin seleccionar' }}</p>
            <a href="{{ route('select-role') }}" class="text-sm text-primary hover:underline">
                Cambiar rol
            </a>
        </div>

        <div>
            <label for="name" class="label">{{ __('Nombre') }}</label>
            <input id="name" maxlength="50"  class="input input-bordered w-full" type="text" name="name" :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

            <div class="mt-4">
                <label for="phone" class="label">{{ __('Teléfono') }}</label>
                <input id="phone" maxlength="20" class="input input-bordered w-full" type="text" name="phone" :value="old('phone')" required />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div class="mt-4">
                <label for="address" class="label">{{ __('Dirección') }}</label>
                <input id="address" maxlength="50" class="input input-bordered w-full" type="text" name="address" :value="old('address')" required />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>
        

        <div class="mt-4">
            <label for="email" class="label">{{ __('Correo electrónico') }}</label>
            <input id="email" class="input input-bordered w-full" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password" class="label">{{ __('Contraseña') }}</label>
            <input id="password" class="input input-bordered w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password_confirmation" class="label">{{ __('Confirmar Contraseña') }}</label>
            <input id="password_confirmation" class="input input-bordered w-full" type="password" name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-primary hover:underline" href="{{ route('login') }}">
                {{ __('¿Ya estás registrado?') }}
            </a>
            <button type="submit" class="btn btn-primary">
                {{ __('Registrarse') }}
            </button>
        </div>
    </form>
</x-guest-layout>
