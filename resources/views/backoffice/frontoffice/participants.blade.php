<div class="mb-2">
    <strong>ID du groupe :</strong> {{ $group->id }}
</div>
<div class="mb-4">
    <strong>Demandes en attente pour ce groupe :</strong> {{ $pendingUsers->count() }}
</div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pendingUsers as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge bg-warning">En attente</span>
                </td>
                <td>
                    <form action="{{ url('admin/groups/accept/' . $group->id . '/' . $user->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Accepter</button>
                    </form>
                    <form action="{{ url('admin/groups/refuse/' . $group->id . '/' . $user->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Refuser</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">Aucune demande d'adhésion en attente pour ce groupe.</td>
            </tr>
        @endforelse
    </tbody>
</table>