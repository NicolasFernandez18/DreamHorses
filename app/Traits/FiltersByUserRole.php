<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Horse;
use App\Models\User;


trait FiltersByUserRole
{
   
    public function filterByUserRole($query)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return $query->whereRaw('1 = 0'); 
        }

        if ($user->hasRole('admin')) {
            return $query; 
        }

        if ($user->hasRole('boss')) {
            return $query->whereHas('horse', function ($q) use ($user) {
                $q->where('boss_id', $user->id);
            });
        }

        if ($user->hasRole('caretaker')) {
            return $query->whereHas('horse', function ($q) use ($user) {
                $q->where('caretaker_id', $user->id);
            });
        }

        return $query;
    }

    public function getUserHorses()
    {
        /** @var \App\Models\User $user */
         
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            return Horse::all();
        }

        if ($user->hasRole('boss')) {
            return Horse::where('boss_id', $user->id)->get();
        }

        if ($user->hasRole('caretaker')) {
            return Horse::where('caretaker_id', $user->id)->get();
        }

        return Horse::all();
    }
}

