<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GroupPostController extends Controller
{
    // Afficher la page du groupe avec les posts
    public function show($group_id)
    {
        $group = \App\Models\Group::findOrFail($group_id);
        // Vérifier que l'utilisateur est membre
        $isMember = $group->users()->where('users.id', auth()->id())->wherePivot('status', 'accepted')->exists();
        if (!$isMember) {
            abort(403, 'Accès réservé aux membres du groupe');
        }
        $posts = \App\Models\Post::where('group_id', $group_id)
            ->with(['user', 'comments.user'])
            ->latest()->get();
    $memberCount = $group->users()->wherePivot('status', 'accepted')->count();
    $recentMembers = $group->users()->wherePivot('status', 'accepted')->orderByDesc('group_user.created_at')->take(8)->get();
    return view('frontoffice.group_wall', compact('group', 'posts', 'memberCount', 'recentMembers'));
    }

    // Publier un nouveau post
    public function store(Request $request, $group_id)
    {
        $group = \App\Models\Group::findOrFail($group_id);
        $isMember = $group->users()->where('users.id', auth()->id())->wherePivot('status', 'accepted')->exists();
        if (!$isMember) {
            abort(403, 'Accès réservé aux membres du groupe');
        }
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);
        \App\Models\Post::create([
            'group_id' => $group_id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);
        return redirect()->route('frontoffice.group.wall', $group_id)->with('success', 'Post publié !');

    }

    // Enregistrer un commentaire sur un post de groupe
    public function comment(Request $request, $group_id, $post_id)
    {
        $group = \App\Models\Group::findOrFail($group_id);
        $post = \App\Models\Post::findOrFail($post_id);
        $isMember = $group->users()->where('users.id', auth()->id())->wherePivot('status', 'accepted')->exists();
        if (!$isMember) {
            abort(403, 'Accès réservé aux membres du groupe');
        }
        $request->validate([
            'content' => 'nullable|string|max:2000',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf|max:4096',
        ]);
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('comments', 'public');
        }
        \App\Models\Comment::create([
            'post_id' => $post_id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'file' => $filePath,
        ]);
        return redirect()->route('frontoffice.group.wall', $group_id)->with('success', 'Commentaire publié !');
}
}