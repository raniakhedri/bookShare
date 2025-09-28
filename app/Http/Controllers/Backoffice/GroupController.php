<?php

namespace App\Http\Controllers\Backoffice;

use App\Models\Group;
use Illuminate\Http\Request;

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
        return view('backoffice.frontoffice.groups', compact('groups', 'totalMembers', 'averageMembers', 'themes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    return view('backoffice.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'theme' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('groups', 'public');
        }
        Group::create($data);
    return redirect()->route('admin.groups')->with('success', 'Group created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
    return view('backoffice.groups.show', compact('group'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
    return view('backoffice.groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'theme' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('groups', 'public');
        }
        $group->update($data);
    return redirect()->route('admin.groups')->with('success', 'Group updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
    $group->delete();
    return redirect()->route('admin.groups')->with('success', 'Group deleted successfully');
    }
    /**
     * Accept a pending group membership request.
     */
    public function acceptMember($group_id, $user_id)
    {
        $group = Group::findOrFail($group_id);
        $user = \App\Models\User::findOrFail($user_id);
        $group->users()->updateExistingPivot($user->id, ['status' => 'accepted']);
        return back()->with('success', 'Membre accepté.');
    }

    /**
     * Refuse a pending group membership request.
     */
    public function refuseMember($group_id, $user_id)
    {
        $group = Group::findOrFail($group_id);
        $user = \App\Models\User::findOrFail($user_id);
        $group->users()->updateExistingPivot($user->id, ['status' => 'refused']);
        return back()->with('success', 'Demande refusée.');
    }
    /**
     * Return the participants list for a group (AJAX/modal).
     */
    public function participants($group_id)
    {
        $group = Group::findOrFail($group_id);
        $pendingUsers = $group->users()->wherePivot('status', 'pending')->get();
        return view('backoffice.frontoffice.participants', compact('group', 'pendingUsers'));
    }
}
