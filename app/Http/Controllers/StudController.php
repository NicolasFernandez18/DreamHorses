<?php

namespace App\Http\Controllers;

use App\Models\Stud;
use App\Http\Requests\StoreStudRequest;
use App\Http\Requests\UpdateStudRequest;
use Illuminate\Support\Facades\Auth;
use App\Traits\FiltersByUserRole;
use Illuminate\Http\Request;
use App\Models\Horse;
use App\Models\User;
use App\Notifications\HireRequestResponseNotification;
use App\Notifications\StudHireRequestNotification;

class StudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use FiltersByUserRole;

    public function index()
    {
        $studs = Stud::with('owner')->paginate(10);
        return view('studs.index', compact('studs'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      return view('studs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(StoreStudRequest $request)
{
    $user = Auth::user();

    if ($user->ownedStud) {
        return back()->with('error', 'Solo podés crear un stud como propietario.');
    }

    $data = $request->validated();
    $data['owner_id'] = $user->id;

    $stud = Stud::create($data);

    $stud->caretakers()->attach($user->id);

    return redirect()->route('studs.show', $stud)
                     ->with('success', 'Stud creado correctamente.');
}



    /**
     * Display the specified resource.
     */
    public function show(Stud $stud)
    {
        $stud->load('caretakers', 'owner', 'pendingBosses');
        return view('studs.show', compact('stud'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stud $stud)
    {
       if (Auth::id() !== $stud->owner_id) {
        abort(403, 'No puedes editar un stud que no te pertenece.');
    }

    return view('studs.edit', compact('stud'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudRequest $request, Stud $stud)
    {
        if (Auth::id() !== $stud->owner_id) {
        abort(403, 'No puedes editar un stud que no te pertenece.');
    }

    $stud->update($request->validated());

    return redirect()->route('studs.show', $stud)
                     ->with('success', 'Stud actualizado correctamente.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stud $stud)
    {
          $user = Auth::user();
        if ($stud->owner_id != $user->id) {
            abort(403);
        }
        $stud->delete();
        return redirect()->route('studs.index')->with('success', 'Stud eliminado.');
    }

  public function join(Stud $stud)
{
    /** @var \App\Models\User $user */

    $user = Auth::user();

    if ($user->studs()->count() >= 2) {
        return back()->with('error', 'Solo podés unirte a un máximo de 2 studs.');
    }

    if (! $stud->caretakers()->where('user_id', $user->id)->exists()) {
        $stud->caretakers()->attach($user->id);
    }

    return back()->with('success', 'Te uniste al stud.');
}


    public function leave(Stud $stud)
    {
        $user = Auth::user();
    
        if ($stud->owner_id == $user->id) {
            return back()->with('error', 'El dueño no puede renunciar. Transferí o eliminá el stud.');
        }
        $stud->caretakers()->detach($user->id);
        return back()->with('success', 'Renunciaste del stud.');
    }

    public function kick(Request $request, Stud $stud)
    {
        $user = Auth::user();
        if ($stud->owner_id != $user->id) {
            abort(403);
        }

        $request->validate([
            'caretaker_id' => 'required|exists:users,id',
        ]);

        $stud->caretakers()->detach($request->caretaker_id);
        return back()->with('success', 'Cuidador despedido.');
    }
    public function hire(Stud $stud)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (! $user->can('studs.hire')) {
            abort(403);
        }

        // Check if a relationship already exists
        $existingContract = $user->contractedStuds()->where('stud_id', $stud->id)->first();

        if ($existingContract) {
            $status = $existingContract->pivot->status;
            if ($status === 'pending') {
                return back()->with('info', 'Ya tienes una solicitud de contrato pendiente para este stud.');
            } elseif ($status === 'accepted') {
                return back()->with('info', 'Ya has contratado este stud.');
            } elseif ($status === 'rejected') {
                // If rejected, allow sending a new request by updating the status to pending
                $user->contractedStuds()->updateExistingPivot($stud->id, ['status' => 'pending']);
                $stud->owner->notify(new StudHireRequestNotification($user, $stud));
                return back()->with('success', 'Tu solicitud de contrato ha sido enviada nuevamente.');
            }
        }

        // If no relationship exists, create a new one
        $user->contractedStuds()->attach($stud->id, ['status' => 'pending']);

        $stud->owner->notify(new StudHireRequestNotification($user, $stud));

        return back()->with('success', 'Tu solicitud de contrato ha sido enviada al dueño del stud.');
    }

public function fire(Stud $stud)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();
    if (! $user->can('studs.fire')) abort(403);

    $caretakerIds = $stud->caretakers->pluck('id');

    Horse::where('boss_id', $user->id)
        ->whereIn('caretaker_id', $caretakerIds)
        ->update(['caretaker_id' => null]);

    $user->contractedStuds()->detach($stud->id);

    return back()->with('success', 'Has dejado de contratar este stud. Los caballos ahora están sin cuidador.');
}

public function respondToHireRequest(Request $request, Stud $stud, User $boss)
    {
        $user = Auth::user();
        if (! $user || ! $user->can('studs.respond')) {
            abort(403);
        }

        if (Auth::id() !== $stud->owner_id) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $stud->bosses()->updateExistingPivot($boss->id, [
            'status' => $request->status,
        ]);

        $boss->notify(new HireRequestResponseNotification($stud, $request->status));

        if ($request->status === 'accepted') {
            return back()->with('success', 'Has aceptado la solicitud de contrato.');
        } else {
            return back()->with('info', 'Has rechazado la solicitud de contrato.');
        }
    }



}
