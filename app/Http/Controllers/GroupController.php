<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $groups = Group::all();
    return view('backoffice.groups.index', compact('groups'));
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
        ]);
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
        ]);
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
}
