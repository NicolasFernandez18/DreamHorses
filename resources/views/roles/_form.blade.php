@php
    $isEdit = isset($role) && $role->exists;
    $selected = old('permissions', $selectedPermissions ?? []);
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="grid grid-cols-1 gap-6">
        <div class="form-control">
            <label class="label" for="name">
                <span class="label-text font-semibold">Nombre del rol</span>
            </label>
            <input id="name" name="name" type="text" value="{{ old('name', $role->name ?? '') }}"
                class="input input-bordered w-full" @if (($isSystemRole ?? false) && $isEdit) readonly @endif />
            @error('name')
                <span class="text-error text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-semibold">Permisos</h3>
                <p class="text-sm text-base-content/70">Marca las vistas y acciones que podrá usar este rol.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($permissionSections as $sectionName => $permissions)
                    <div class="card bg-base-200 border border-base-300 shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="font-bold text-primary mb-2">{{ $sectionName }}</h4>
                            <div class="space-y-2">
                                @foreach ($permissions as $permissionName => $label)
                                    <label class="flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-base-300">
                                        <input type="checkbox" name="permissions[]" value="{{ $permissionName }}"
                                            class="checkbox checkbox-primary"
                                            {{ in_array($permissionName, $selected, true) ? 'checked' : '' }} />
                                        <span class="text-sm">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @error('permissions')
                <span class="text-error text-sm">{{ $message }}</span>
            @enderror
            @error('permissions.*')
                <span class="text-error text-sm">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn btn-primary">
            {{ $submitLabel }}
        </button>
        <a href="{{ route('roles.index') }}" class="btn btn-ghost">Cancelar</a>
    </div>
</form>
