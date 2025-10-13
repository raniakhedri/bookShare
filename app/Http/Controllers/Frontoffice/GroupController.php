<?php

namespace App\Http\Controllers\Frontoffice;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::with('users')->get();
        // Ajout du nombre de membres pour chaque groupe
        foreach ($groups as $group) {
            $group->members_count = $group->users->count();
        }
        $totalMembers = $groups->sum('members_count');
        $averageMembers = $groups->count() > 0 ? round($totalMembers / $groups->count(), 1) : 0;
        $themes = $groups->pluck('theme')->unique();
        return view('frontoffice.groups', compact('groups', 'totalMembers', 'averageMembers', 'themes'));
    }
}
