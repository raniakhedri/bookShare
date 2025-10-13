@extends('backoffice.layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header simplifié -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">👥 Gestion des Groupes</h5>
                            <p class="text-muted mb-0">Gérez vos groupes de lecture</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.groups.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                                <i class="fa-solid fa-plus fa-fw"></i>
                                Ajouter un groupe
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Statistiques simplifiées -->
                <div class="card-body bg-light rounded-bottom">
                    <div class="row text-center">
                        <div class="col-6 col-md-3 mb-3">
                            <div class="border-end border-gray-300 pe-3">
                                <h6 class="text-sm text-muted mb-1">Total Groupes</h6>
                                <h4 class="fw-bold mb-0 text-dark">{{ $groups->count() }}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="border-end border-gray-300 pe-3">
                                <h6 class="text-sm text-muted mb-1">Thèmes uniques</h6>
                                <h4 class="fw-bold mb-0 text-success">{{ $groups->pluck('theme')->unique()->count() }}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="border-end border-gray-300 pe-3">
                                <h6 class="text-sm text-muted mb-1">Membres total</h6>
                                <h4 class="fw-bold mb-0 text-primary">{{ $totalMembers }}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div>
                                <h6 class="text-sm text-muted mb-1">Moyenne membres</h6>
                                <h4 class="fw-bold mb-0 text-warning">{{ $averageMembers }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau simplifié -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <!-- Barre de recherche simple -->
                    <div class="bg-white px-4 py-3 border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-solid fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" placeholder="Rechercher un groupe..." id="searchInput">
                                </div>
                            </div>
                            <div class="col-md-6 text-end mt-2 mt-md-0">
                                <div class="d-flex gap-2 justify-content-end">
                                    <select class="form-select form-select-sm w-auto border" id="themeFilter">
                                        <option value="">Tous les thèmes</option>
                                        @foreach($themes as $theme)
                                            <option value="{{ $theme }}">{{ $theme }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-items-center mb-0" id="groupsTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 text-uppercase text-muted text-xs fw-bold">Nom</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold">Thème</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold">Description</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold text-center">Membres</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold">Date création</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groups as $group)
                                <tr class="border-bottom">
                                    <!-- Nom -->
                                    <td class="ps-4">
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-0 text-sm fw-bold text-dark">
                                                {{ $group->name }}
                                            </h6>
                                            <p class="text-xs text-muted mb-0">
                                                ID: {{ $group->id }}
                                            </p>
                                        </div>
                                    </td>

                                    <!-- Thème -->
                                    <td>
                                        <span class="badge bg-primary text-white">
                                            {{ $group->theme }}
                                        </span>
                                    </td>

                                    <!-- Description -->
                                    <td>
                                        <span class="text-sm text-dark">
                                            {{ $group->description ?: 'Aucune description' }}
                                        </span>
                                    </td>

                                    <!-- Membres -->
                                    <td class="text-center">
                                        <span class="badge bg-info text-white">
                                            {{ $group->members_count ?? 0 }} membres
                                        </span>
                                    </td>

                                    <!-- Date création -->
                                    <td>
                                        <span class="text-sm text-muted">
                                            {{ $group->created_at->format('d/m/Y') }}
                                        </span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Modifier -->
                                            <a href="{{ url('admin/groups/editGroup/' . $group->id) }}" 
                                               class="btn btn-sm btn-outline-primary rounded"
                                               data-bs-toggle="tooltip" 
                                               data-bs-title="Modifier">
                                                <span title="Edit" style="font-size:1.1em;">✏️</span>
                                            </a>

                                            <!-- Participants -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-info rounded" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#participantsModal{{ $group->id }}"
                                                    data-bs-toggle="tooltip" 
                                                    data-bs-title="Voir les participants">
                                                <span title="Participants" style="font-size:1.1em;">👥</span>
                                            </button>

                                            <!-- Supprimer -->
                                            <form action="{{ route('admin.groups.destroy', $group) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger rounded"
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-title="Supprimer"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce groupe ?')">
                                                    <span title="Delete" style="font-size:1.1em;">🗑️</span>
                                                </button>
                                            </form>

                                            <!-- Voir détails -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-secondary rounded" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailsModal{{ $group->id }}"
                                                    data-bs-toggle="tooltip" 
                                                    data-bs-title="Détails">
                                                <span title="View" style="font-size:1.1em;">👁️</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($groups, 'hasPages') && $groups->hasPages())
                    <div class="card-footer bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="text-sm text-muted mb-0">
                                Affichage de {{ $groups->firstItem() }} à {{ $groups->lastItem() }} sur {{ $groups->total() }} groupes
                            </p>
                            <nav aria-label="Page navigation">
                                {{ $groups->links('vendor.pagination.bootstrap-5') }}
                            </nav>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour chaque groupe -->
@foreach($groups as $group)
<!-- Details Modal -->
<div class="modal fade" id="detailsModal{{ $group->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $group->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailsModalLabel{{ $group->id }}">Détails du Groupe</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="fw-bold mb-3 text-primary">{{ $group->name }}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Thème:</strong> <span class="badge bg-primary">{{ $group->theme }}</span></p>
                                <p class="mb-2"><strong>Date de création:</strong> {{ $group->created_at->format('d/m/Y H:i') }}</p>
                                <p class="mb-2"><strong>Dernière modification:</strong> {{ $group->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Nombre de membres:</strong> <span class="badge bg-info">{{ $group->members_count ?? 0 }}</span></p>
                            </div>
                        </div>
                        <hr>
                        <p class="mb-1"><strong>Description:</strong></p>
                        <p class="mb-0 text-muted">{{ $group->description ?: 'Aucune description fournie' }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="{{ url('admin/groups/editGroup/' . $group->id) }}" class="btn btn-primary">Modifier</a>
            </div>
        </div>
    </div>
</div>

<!-- Participants Modal -->
<div class="modal fade" id="participantsModal{{ $group->id }}" tabindex="-1" aria-labelledby="participantsModalLabel{{ $group->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="participantsModalLabel{{ $group->id }}">
                    <span style="font-size:1.1em;">👥</span>
                    Participants du groupe : {{ $group->name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($group->users && $group->users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-xs fw-bold">Nom</th>
                                    <th class="text-uppercase text-xs fw-bold">Email</th>
                                    <th class="text-uppercase text-xs fw-bold text-center">Statut</th>
                                    <th class="text-uppercase text-xs fw-bold text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($group->users as $user)
                                    <tr>
                                        <td class="align-middle fw-medium">{{ $user->name }}</td>
                                        <td class="align-middle">{{ $user->email }}</td>
                                        <td class="align-middle text-center">
                                            @if($user->pivot->status === 'accepted')
                                                <span class="badge bg-success">Accepté</span>
                                            @elseif($user->pivot->status === 'refused')
                                                <span class="badge bg-danger">Refusé</span>
                                            @else
                                                <span class="badge bg-warning text-dark">En attente</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($user->pivot->status === 'pending')
                                                <div class="d-flex justify-content-center gap-2">
                                                    <form action="{{ url('admin/groups/accept/' . $group->id . '/' . $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-title="Accepter">
                                                            <span style="font-size:1.1em;">✅</span> Accepter
                                                        </button>
                                                    </form>
                                                    <form action="{{ url('admin/groups/refuse/' . $group->id . '/' . $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-title="Refuser">
                                                            <span style="font-size:1.1em;">❌</span> Refuser
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-muted fst-italic">Aucune action</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Résumé des statuts -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-center gap-4">
                                <div class="text-center">
                                    <span class="badge bg-success">Acceptés: {{ $group->users->where('pivot.status', 'accepted')->count() }}</span>
                                </div>
                                <div class="text-center">
                                    <span class="badge bg-warning text-dark">En attente: {{ $group->users->where('pivot.status', 'pending')->count() }}</span>
                                </div>
                                <div class="text-center">
                                    <span class="badge bg-danger">Refusés: {{ $group->users->where('pivot.status', 'refused')->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <span style="font-size:3em;">👥</span>
                        <h5 class="text-muted">Aucun participant dans ce groupe</h5>
                        <p class="text-muted">Les utilisateurs peuvent demander à rejoindre ce groupe.</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <span style="font-size:1.1em;">❌</span> Fermer
                </button>
                <small class="text-muted me-auto">
                    Total: {{ $group->users->count() }} participant(s)
                </small>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
// Real-time search
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const themeFilter = document.getElementById('themeFilter');
    
    function filterGroups() {
        const searchValue = searchInput.value.toLowerCase();
        const themeValue = themeFilter.value.toLowerCase();
        const rows = document.querySelectorAll('#groupsTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const theme = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            
            const matchesSearch = text.includes(searchValue);
            const matchesTheme = !themeValue || theme.includes(themeValue);
            
            row.style.display = (matchesSearch && matchesTheme) ? '' : 'none';
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', filterGroups);
    }
    
    if (themeFilter) {
        themeFilter.addEventListener('change', filterGroups);
    }

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush

@push('styles')
<!-- Load Font Awesome from CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Styles cohérents avec la vue books */
.card {
    border-radius: 12px;
}

.table th {
    font-weight: 600;
    font-size: 0.75rem;
    padding: 1rem 0.75rem;
    border-bottom: 2px solid #e9ecef;
}

.table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #e9ecef;
}

.badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.7rem;
    font-weight: 500;
}

.btn-sm {
    padding: 0.35rem 0.75rem;
    font-size: 0.8rem;
    min-width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-sm span {
    font-size: 1.1em;
    line-height: 1;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.text-muted {
    color: #6c757d !important;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.btn:hover {
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

/* Ensure icons display correctly */
.fas, .far, .fab {
    font-family: "Font Awesome 6 Free" !important;
    font-weight: 900;
    font-style: normal;
}

.fa-fw {
    width: 1.25em;
    text-align: center;
}

/* Responsive */
@media (max-width: 768px) {
    .card-header .d-flex {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .btn-sm {
        min-width: 32px;
        height: 32px;
        padding: 0.25rem 0.5rem;
    }
    
    .btn-sm span {
        font-size: 1em;
    }
    
    .table th, .table td {
        padding: 0.75rem 0.5rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.3rem 0.5rem;
    }
}

/* Improved contrast */
.text-dark {
    color: #212529 !important;
}

.border {
    border-color: #dee2e6 !important;
}

/* Icon alignment */
.d-flex.align-items-center i {
    line-height: 1;
}
</style>
@endpush