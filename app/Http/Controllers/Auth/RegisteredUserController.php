<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Caretaker; 
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;


class RegisteredUserController extends Controller
{
    private function registrationRoles(): Collection
    {
        return Role::query()
            ->where('name', '!=', 'admin')
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get()
            ->map(function (Role $role) {
                return (object) [
                    'value' => $role->name,
                    'label' => $this->roleLabel($role->name),
                ];
            });
    }

    private function roleLabel(string $roleName): string
    {
        return match ($roleName) {
            'boss' => 'Jefe',
            'caretaker' => 'Cuidador',
            default => Str::of($roleName)->replace(['_', '-'], ' ')->title()->toString(),
        };
    }

    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $roles = $this->registrationRoles();
        $selectedRole = $request->query('role');

        if (! $selectedRole || ! $roles->contains('value', $selectedRole)) {
            return view('auth.select-role', [
                'roles' => $roles,
            ]);
        }

        return view('auth.register', [
            'role' => $selectedRole,
            'roleLabel' => $this->roleLabel($selectedRole),
            'roles' => $roles,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => [
                'required',
                'string',
                Rule::exists('roles', 'name')->where(function ($query) {
                    $query->where('guard_name', 'web')
                        ->where('name', '!=', 'admin');
                }),
            ],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255']

        ], [
            'name.required' => 'El campo de nombre es obligatorio.',
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.email' => 'Por favor, introduce una dirección de correo electrónico válida.',
            'email.unique' => 'Esta dirección de correo electrónico ya está en uso.',
            'password.required' => 'El campo de contraseña es obligatorio.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'phone.required' => 'El campo de teléfono es obligatorio.',
            'address.required' => 'El campo de dirección es obligatorio.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address


        ]);
        $user->assignRole($request->role);


        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
