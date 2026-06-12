<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHorseRequest;
use App\Http\Requests\UpdateHorseRequest;
use App\Models\Horse;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Race;
use App\Models\VetVisit;
use App\Models\CalendarEvent;
use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;

class HorseController extends Controller
{
    private function selectableCaretakers(?User $user)
    {
        if (! $user) {
            return collect();
        }

        if ($user->hasRole('admin')) {
            return User::role('caretaker')->get();
        }

        if ($user->hasRole('boss')) {
            return User::whereHas('studs', function ($query) use ($user) {
                $query->whereIn('stud_id', $user->acceptedContractedStuds->pluck('id'));
            })->role('caretaker')->get();
        }

        if ($user->hasRole('caretaker')) {
            return collect([$user]);
        }

        return User::role('caretaker')->get();
    }

    /**
     * Display a listing of the resource.
     */

public function index()
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    if ($user && $user->hasRole('admin')) {
        $horses = Horse::with(['boss', 'caretaker'])->latest()->paginate(6);
    } elseif ($user && ($user->hasRole('boss') || $user->hasRole('caretaker'))) {
        $horses = Horse::with(['boss', 'caretaker'])
            ->when($user->hasRole('boss'), function ($query) use ($user) {
                $query->where('boss_id', $user->id);
            })
            ->when($user->hasRole('caretaker'), function ($query) use ($user) {
                $query->orWhere('caretaker_id', $user->id);
            })
            ->latest()
            ->paginate(6);
    } else {
        $horses = Horse::with(['boss', 'caretaker'])->latest()->paginate(6);
    }

    return view('Horse.index', compact('horses'));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $caretakers = $this->selectableCaretakers($user);

        return view('Horse.create', compact('caretakers'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHorseRequest $request)
{
    $data = $request->validated();

    if ($request->hasFile('photo')) {
        $data['photo_path'] = $request->file('photo')->store('horses', 'public');
    }
    /** @var \App\Models\User $user */

    $user = Auth::user();

    if ($user->hasRole('boss')) {
        $data['boss_id'] = $user->id;
    }

    if ($user->hasRole('caretaker')) {
        $data['caretaker_id'] = $user->id;
    } elseif ($request->filled('caretaker_id')) {
        $data['caretaker_id'] = $request->input('caretaker_id');
    }

    $horse = Horse::create($data);

    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $image) {
            $path = $image->store('horses', 'public');
            $horse->photos()->create(['path' => $path]);
        }
    }

    return redirect()->route('Horseindex')->with('success', 'Caballo creado correctamente.');
}

    /**
     * Display the specified resource.
     */
    public function show(Horse $horse)
    {
            return view('Horse.show', compact('horse'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Horse $horse)
{
    /** @var \App\Models\User $user */
     $user = Auth::user();
    $caretakers = $this->selectableCaretakers($user);

    return view('Horse.edit', compact('horse', 'caretakers'));
}

public function update(UpdateHorseRequest $request, Horse $horse)
{
    $data = $request->validated();

    $horse->update($data);

    if ($request->hasFile('photos')) {
        foreach ($horse->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
        }

        foreach ($request->file('photos') as $image) {
            $path = $image->store('horses', 'public');
            $horse->photos()->create(['path' => $path]);
        }
    }
    

    return redirect()->route('horses.show', $horse->id)
                     ->with('info', 'Caballo actualizado correctamente');
}


public function destroy(Horse $horse)
{
    foreach ($horse->photos as $photo) {
        Storage::disk('public')->delete($photo->path);
        $photo->delete();
    }
    if ($horse->photo_path ?? false) {
        Storage::disk('public')->delete($horse->photo_path);
    }

    $horse->delete();
    return redirect()->route('Horseindex')->with('error', 'Caballo eliminado correctamente ');
}

public function downloadPDF(Horse $horse)
{
    $horse->load('photos');

    $nextRaces = Race::where('horse_id', $horse->id)
        ->where('date', '>=', now())
        ->orderBy('date')->get();

    $nextVetVisits = VetVisit::where('horse_id', $horse->id)
        ->where('visit_date', '>=', now())
        ->orderBy('visit_date')->get();

    $nextCalendarEvents = CalendarEvent::where('horse_id', $horse->id)
        ->where('event_date', '>=', now())
        ->orderBy('event_date')->get();

    $events = $nextRaces->map(fn($r) => (object)[
        'horse_id' => $r->horse_id,
        'event_date' => $r->date,
        'title' => 'Carrera en ' . $r->hipodromo,
        'category' => 'Carrera',
    ])->concat(
        $nextVetVisits->map(fn($v) => (object)[
            'horse_id' => $v->horse_id,
            'event_date' => $v->visit_date,
            'title' => $v->diagnosis,
            'category' => 'Visita Veterinaria',
        ])
    )->concat(
        $nextCalendarEvents->map(fn($e) => (object)[
            'horse_id' => $e->horse_id,
            'event_date' => $e->event_date,
            'title' => $e->title,
            'category' => $e->category,
        ])
    )->sortBy('event_date');

    $alerts = $events->filter(fn($e) => $e->event_date <= now()->addDays(7));

    $totalExpenses = Expense::where('horse_id', $horse->id)
        ->sum('amount');

    $pdf = Pdf::loadView('Horse.pdf', compact('horse', 'events', 'totalExpenses', 'alerts'));

    return $pdf->download('dreamhorses-' . $horse->name . '.pdf');
}
}
