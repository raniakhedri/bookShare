<?php

namespace App\Http\Controllers\Frontoffice;


use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;

class GroupJoinController extends Controller
{
    public function join($id)
    {
        $group = Group::findOrFail($id);
        $user = Auth::user();
        // Ici, on suppose qu'il existe une table group_user ou une relation pour stocker la demande
        // À adapter selon votre structure
        if ($group->users()->where('user_id', $user->id)->exists()) {
            return Redirect::back()->with('success', 'Vous êtes déjà membre ou en attente.');
        }
        // Enregistre la demande d'adhésion (statut: en attente)
        $group->users()->attach($user->id, ['status' => 'pending']);
        return Redirect::back()->with('success', 'Demande envoyée à l\'admin.');
    }
}
