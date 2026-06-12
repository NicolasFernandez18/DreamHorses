<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar Caballo - DreamHorses</title>
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
            <div class="p-6 md:p-8">
                <h1 class="text-3xl font-bold mb-6 text-base-content"> Editar Caballo: {{ $horse->name }}
                </h1>
                <a href="{{ route('Horseindex') }}" class="btn btn-accent ml-2 shadow-sm">Volver
                    a la Lista</a>

                <x-session-alert />

                <form action="{{ route('horses.update', $horse->id) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4 bg-base-200 p-6 rounded-lg mt-4 shadow-md">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <fieldset class="fieldset">
                            <legend class="text-base-content/80">Nombre</legend>
                            <input type="text" maxlength="50" name="name" value="{{ old('name', $horse->name) }}"
                                placeholder="Nombre" class="input input-bordered w-full" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="text-base-content/80">Raza</legend>
                            <input type="text" name="breed" value="{{ old('breed', $horse->breed) }}"
                                placeholder="Raza" class="input input-bordered w-full" required />
                            <x-input-error :messages="$errors->get('breed')" class="mt-2" />
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="text-base-content/80">Color</legend>
                            <input type="text" name="color" value="{{ old('color', $horse->color) }}"
                                placeholder="Color" class="input input-bordered w-full" required />
                            <x-input-error :messages="$errors->get('color')" class="mt-2" />
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="text-base-content/80">Fecha de Nacimiento</legend>
                            <input type="date" name="birth_date" value="{{ old('birth_date', $horse->birth_date) }}"
                                class="input input-bordered w-full" required />
                            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="text-base-content/80">Género</legend>
                            <select name="gender" class="select select-bordered w-full" required>
                                <option value="male" {{ $horse->gender == 'male' ? 'selected' : '' }}>Macho</option>
                                <option value="female" {{ $horse->gender == 'female' ? 'selected' : '' }}>Hembra
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="text-base-content/80">Microchip</legend>
                            <input type="text" name="number_microchip"
                                value="{{ old('number_microchip', $horse->number_microchip) }}"
                                placeholder="Número de Microchip" class="input input-bordered w-full" />
                            <x-input-error :messages="$errors->get('number_microchip')" class="mt-2" />
                        </fieldset>



                        <fieldset class="fieldset">
                            <legend class="text-base-content/80">Padre</legend>
                            <input type="text" maxlength="50" name="father_name"
                                value="{{ old('father_name', $horse->father_name) }}" placeholder="Padre"
                                class="input input-bordered w-full" required />
                            <x-input-error :messages="$errors->get('father_name')" class="mt-2" />
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="text-base-content/80">Madre</legend>
                            <input type="text" maxlength="50" name="mother_name"
                                value="{{ old('mother_name', $horse->mother_name) }}" placeholder="Madre"
                                class="input input-bordered w-full" required />
                            <x-input-error :messages="$errors->get('mother_name')" class="mt-2" />
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="text-base-content/80">Cuidador</legend>

                            @if (auth()->user()?->hasRole('caretaker'))
                                <input type="hidden" name="caretaker_id" value="{{ auth()->id() }}">
                                <input type="text" class="input input-bordered w-full" value="{{ auth()->user()->name }}" readonly />
                            @else
                                <select name="caretaker_id" class="select select-bordered w-full">
                                    @foreach ($caretakers as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('caretaker_id', $horse->caretaker_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif

                            <x-input-error :messages="$errors->get('caretaker_id')" class="mt-2" />
                        </fieldset>
                    </div>

                    <div class="space-y-2 mt-6">
                        <label class="text-base-content/80 font-semibold"> Fotos actuales:</label>
                        @if ($horse->photos->count())
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach ($horse->photos as $photo)
                                    <img src="{{ asset('storage/' . $photo->path) }}"
                                        class="w-full h-32 object-cover rounded-lg border-2 border-base-300" />
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-base-content/60">Este caballo no tiene fotos guardadas.</p>
                        @endif
                    </div>


                    <fieldset class="fieldset mt-6">
                        <legend class="text-base-content/80">Cambiar fotos (Maximo 1MB):</legend>
                        <input type="file" name="photos[]" id="photos" multiple accept="image/*"
                            class="file-input file-input-bordered w-full" onchange="previewImages(event)" />
                        <div id="preview" class="flex flex-wrap gap-2 mt-2"></div>
                        <x-input-error :messages="$errors->get('photos')" class="mt-2" />
                    </fieldset>
                    <script>
                        function previewImages(event) {
                            const preview = document.getElementById('preview');
                            preview.innerHTML = '';
                            const files = event.target.files;
                            if (files) {
                                Array.from(files).forEach(file => {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        const img = document.createElement('img');
                                        img.src = e.target.result;
                                        img.className = "rounded shadow";
                                        img.style.width = "120px";
                                        img.style.height = "90px";
                                        img.style.objectFit = "cover";
                                        preview.appendChild(img);
                                    }
                                    reader.readAsDataURL(file);
                                });
                            }
                        }
                    </script>


                    <button type="submit" class="btn btn-warning w-full mt-6 shadow-sm">
                        Actualizar Caballo
                    </button>
                </form>
            </div>
        </div>

        <x-sidebar />
    </div>
</body>

</html>
