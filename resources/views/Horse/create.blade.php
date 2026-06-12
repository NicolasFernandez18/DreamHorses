<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Caballo - DreamHorses</title>
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
            <!-- Hamburger button -->
            <label for="my-drawer" class="btn btn-primary drawer-button lg:hidden m-4 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </label>

            <!-- Main content -->
            <div class="p-6 md:p-8 max-w-4xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">Registrar Caballo</h1>
                    <a href="{{ route('Horseindex') }}" class="btn btn-accent mt-4 sm:mt-0 shadow-sm">Volver a la
                        Lista</a>
                </div>

                <x-session-alert />

                <form action="{{ route('StoreHorse') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4 bg-base-200 p-6 rounded-xl shadow-md">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Name --}}
                        <div class="form-control">
                            <label for="name" class="label">
                                <span class="label-text">Nombre</span>
                            </label>
                            <input type="text" maxlength="50" name="name" id="name" class="input input-bordered w-full"
                                placeholder="Ej. Relámpago" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Breed --}}
                        <div class="form-control">
                            <label for="breed" class="label">
                                <span class="label-text">Raza</span>
                            </label>
                            <input type="text" name="breed" id="breed" class="input input-bordered w-full"
                                placeholder="Ej. Pura Sangre" required />
                            <x-input-error :messages="$errors->get('breed')" class="mt-2" />
                        </div>

                        {{-- Color --}}
                        <div class="form-control">
                            <label for="color" class="label">
                                <span class="label-text">Color</span>
                            </label>
                            <input type="text" name="color" id="color" class="input input-bordered w-full"
                                placeholder="Ej. Castaño" required />
                            <x-input-error :messages="$errors->get('color')" class="mt-2" />
                        </div>

                        {{-- Microchip --}}
                        <div class="form-control">
                            <label for="number_microchip" class="label">
                                <span class="label-text">Número de Microchip</span>
                            </label>
                            <input type="text" name="number_microchip" id="number_microchip"
                                class="input input-bordered w-full" placeholder="Ej. 1234567AJF" />
                            <x-input-error :messages="$errors->get('number_microchip')" class="mt-2" />
                        </div>

                        {{-- Birth Date --}}
                        <div class="form-control">
                            <label for="birth_date" class="label">
                                <span class="label-text">Fecha de nacimiento</span>
                            </label>
                            <input type="date" name="birth_date" id="birth_date" class="input input-bordered w-full"
                                required />
                            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                        </div>

                        {{-- Gender --}}
                        <div class="form-control">
                            <label for="gender" class="label">
                                <span class="label-text">Género</span>
                            </label>
                            <select name="gender" id="gender" class="select select-bordered w-full" required>
                                <option disabled selected>Seleccione género</option>
                                <option value="male">Macho</option>
                                <option value="female">Hembra</option>
                            </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>

                        {{-- Father --}}
                        <div class="form-control">
                            <label for="father_name" class="label">
                                <span class="label-text">Padre</span>
                            </label>
                            <input type="text"  maxlength="50" name="father_name" id="father_name"
                                class="input input-bordered w-full" placeholder="Ej. Viento" required />
                            <x-input-error :messages="$errors->get('father_name')" class="mt-2" />
                        </div>

                        {{-- Mother --}}
                        <div class="form-control">
                            <label for="mother_name" class="label">
                                <span class="label-text">Madre</span>
                            </label>
                            <input type="text" maxlength="50" name="mother_name" id="mother_name"
                                class="input input-bordered w-full" placeholder="Ej. Rayo" required />
                            <x-input-error :messages="$errors->get('mother_name')" class="mt-2" />
                        </div>

                        {{-- Caretaker --}}
                        <div class="form-control md:col-span-2">
                            <label for="caretaker_id" class="label">
                                <span class="label-text">Cuidador</span>
                            </label>

                            @if (auth()->user()?->hasRole('caretaker'))
                                <input type="hidden" name="caretaker_id" value="{{ auth()->id() }}">
                                <input type="text" class="input input-bordered w-full" value="{{ auth()->user()->name }}" readonly />
                            @else
                                <select name="caretaker_id" id="caretaker_id" class="select select-bordered w-full">
                                    <option disabled selected>Seleccione cuidador</option>
                                    @foreach ($caretakers as $caretaker)
                                        <option value="{{ $caretaker->id }}" {{ old('caretaker_id') == $caretaker->id ? 'selected' : '' }}>
                                            {{ $caretaker->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif

                            <x-input-error :messages="$errors->get('caretaker_id')" class="mt-2" />
                        </div>

                        {{-- Photos --}}
                        <div class="form-control md:col-span-2">
                            <label for="photos" class="label">
                                <span class="label-text">Fotos del Caballo (Maximo 1MB)</span>
                            </label>
                            <input type="file" name="photos[]" id="photos" multiple accept="image/*"
                                class="file-input file-input-bordered w-full" onchange="previewImages(event)" />
                            <div id="preview" class="flex flex-wrap gap-2 mt-2"></div>
                            <x-input-error :messages="$errors->get('photos')" class="mt-2" />
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="btn btn-primary w-full">
                            Guardar Caballo
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <x-sidebar />
    </div>


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
</body>

</html>
