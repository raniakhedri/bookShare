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
                            <h5 class="mb-1 fw-bold text-dark">🏪 Gestion du Marketplace</h5>
                            <p class="text-muted mb-0">Gérez les livres mis en vente sur le marketplace</p>
                        </div>
                    </div>
                </div>

                <!-- Statistiques simplifiées -->
                <div class="card-body bg-light rounded-bottom">
                    <div class="row text-center">
                        <div class="col-6 col-md-3 mb-3">
                            <div class="border-end border-gray-300 pe-3">
                                <h6 class="text-sm text-muted mb-1">Total Livres</h6>
                                <h4 class="fw-bold mb-0 text-dark">
                                    {{ $marketBooks->count() }}
                                </h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="border-end border-gray-300 pe-3">
                                <h6 class="text-sm text-muted mb-1">Disponibles</h6>
                                <h4 class="fw-bold mb-0 text-success">{{ $marketBooks->where('is_available', true)->count() }}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="border-end border-gray-300 pe-3">
                                <h6 class="text-sm text-muted mb-1">Vendus</h6>
                                <h4 class="fw-bold mb-0 text-primary">{{ $marketBooks->where('is_available', false)->count() }}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div>
                                <h6 class="text-sm text-muted mb-1">Demandes</h6>
                                <h4 class="fw-bold mb-0 text-warning">{{ $totalRequests }}</h4>
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
                                    <input type="text" class="form-control border-start-0" placeholder="Rechercher un livre..." id="searchInput">
                                </div>
                            </div>
                            <div class="col-md-6 text-end mt-2 mt-md-0">
                                <div class="d-flex gap-2 justify-content-end">
                                    <select class="form-select form-select-sm w-auto border" id="filterStatus">
                                        <option value="">Tous les statuts</option>
                                        <option value="available">Disponible</option>
                                        <option value="unavailable">Vendu</option>
                                    </select>
                                    <select class="form-select form-select-sm w-auto border" id="filterCondition">
                                        <option value="">Tous les états</option>
                                        <option value="new">Neuf</option>
                                        <option value="excellent">Excellent</option>
                                        <option value="good">Bon</option>
                                        <option value="fair">Correct</option>
                                        <option value="poor">Usé</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-items-center mb-0" id="marketBooksTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 text-uppercase text-muted text-xs fw-bold">Couverture</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold">Livre</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold">Vendeur</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold">Prix</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold text-center">État</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold text-center">Statut</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold text-center">Demandes</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($marketBooks as $book)
                                <tr class="border-bottom">
                                    <!-- Image -->
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xl">
                                                @if($book->image_path)
                                                    <img src="{{ asset('storage/' . $book->image_path) }}" 
                                                         alt="{{ $book->title }}" 
                                                         class="rounded border"
                                                         style="width: 50px; height: 65px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                                         style="width: 50px; height: 65px;">
                                                        <i class="fa-solid fa-book text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Titre -->
                                    <td>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-0 text-sm fw-bold text-dark">
                                                {{ Str::limit($book->title, 25) }}
                                            </h6>
                                            <p class="text-xs text-muted mb-0">
                                                {{ $book->author }}
                                            </p>
                                        </div>
                                    </td>

                                    <!-- Vendeur -->
                                    <td>
                                        <span class="text-sm text-dark">
                                            {{ $book->owner->name }}
                                        </span>
                                    </td>

                                    <!-- Prix -->
                                    <td>
                                        <span class="badge bg-success text-white">
                                            {{ number_format($book->price, 2) }} €
                                        </span>
                                    </td>

                                    <!-- État -->
                                    <td class="text-center">
                                        <span class="badge bg-info text-white">
                                            {{ ucfirst($book->condition) }}
                                        </span>
                                    </td>

                                    <!-- Disponibilité -->
                                    <td class="text-center">
                                        <span class="badge {{ $book->is_available ? 'bg-success' : 'bg-danger' }} text-white">
                                            {{ $book->is_available ? 'Disponible' : 'Vendu' }}
                                        </span>
                                    </td>

                                    <!-- Nombre de demandes -->
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark">
                                            {{ $book->requests_count ?? 0 }}
                                        </span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- View details -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-secondary rounded" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailsModal{{ $book->id }}" 
                                                    title="Details">
                                                <span title="View" style="font-size:1.1em;">👁️</span>
                                            </button>

                                            <!-- Supprimer -->
                                            <form action="{{ route('admin.marketplace.book.delete', $book->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger rounded"
                                                        title="Supprimer">
                                                    <span style="font-size:1.1em;">🗑️</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Book Details Modal -->
                                <div class="modal fade" id="detailsModal{{ $book->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Détails du livre</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-4 text-center mb-3 mb-md-0">
                                                        @if($book->image_path)
                                                            <img src="{{ asset('storage/' . $book->image_path) }}" 
                                                                 alt="{{ $book->title }}" 
                                                                 class="img-fluid rounded shadow" 
                                                                 style="max-height: 220px;">
                                                        @else
                                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                                 style="width: 120px; height: 170px; margin: 0 auto;">
                                                                <span style="font-size:2.5em; color: #fff;">📚</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-8">
                                                        <h5 class="fw-bold mb-2">{{ $book->title }}</h5>
                                                        <p class="mb-1"><strong>Auteur:</strong> {{ $book->author }}</p>
                                                        <p class="mb-1"><strong>Vendeur:</strong> {{ $book->owner->name }}</p>
                                                        <p class="mb-1"><strong>Email:</strong> {{ $book->owner->email }}</p>
                                                        <p class="mb-1"><strong>Prix:</strong> {{ number_format($book->price, 2) }} €</p>
                                                        <p class="mb-1"><strong>État:</strong> {{ ucfirst($book->condition) }}</p>
                                                        <p class="mb-1"><strong>Statut:</strong> 
                                                            <span class="badge {{ $book->is_available ? 'bg-success' : 'bg-danger' }} text-white">
                                                                {{ $book->is_available ? 'Disponible' : 'Vendu' }}
                                                            </span>
                                                        </p>
                                                        <p class="mb-1"><strong>Demandes:</strong> {{ $book->requests_count ?? 0 }}</p>
                                                        <p class="mb-1"><strong>Ajouté le:</strong> {{ $book->created_at->format('d/m/Y H:i') }}</p>
                                                        @if($book->description)
                                                            <hr>
                                                            <p class="mb-0"><strong>Description:</strong><br>{{ $book->description }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                <a href="{{ route('marketplace.books.show', $book->id) }}" 
                                                   class="btn btn-primary" 
                                                   target="_blank">
                                                    Voir sur le marketplace
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <span style="font-size:3em;">🏪</span>
                                            <h6 class="text-secondary mt-2">Aucun livre dans le marketplace</h6>
                                            <p class="text-xs text-secondary">Les utilisateurs n'ont pas encore mis de livres en vente</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($marketBooks, 'hasPages') && $marketBooks->hasPages())
                    <div class="card-footer bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="text-sm text-muted mb-0">
                                Showing {{ $marketBooks->firstItem() }} to {{ $marketBooks->lastItem() }} of {{ $marketBooks->total() }} books
                            </p>
                            <nav aria-label="Page navigation">
                                {{ $marketBooks->links('vendor.pagination.bootstrap-5') }}
                            </nav>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real-time search
    const searchInput = document.getElementById('searchInput');
    const filterStatus = document.getElementById('filterStatus');
    const filterCondition = document.getElementById('filterCondition');
    
    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const statusValue = filterStatus.value.toLowerCase();
        const conditionValue = filterCondition.value.toLowerCase();
        
        const rows = document.querySelectorAll('#marketBooksTable tbody tr');
        
        rows.forEach(row => {
            if (row.querySelector('td[colspan="8"]')) return; // Skip empty state row
            
            const text = row.textContent.toLowerCase();
            const status = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
            const condition = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
            
            const matchesSearch = searchValue === '' || text.includes(searchValue);
            const matchesStatus = statusValue === '' || status.includes(statusValue);
            const matchesCondition = conditionValue === '' || condition.includes(conditionValue);
            
            row.style.display = (matchesSearch && matchesStatus && matchesCondition) ? '' : 'none';
        });
    }
    
    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (filterStatus) filterStatus.addEventListener('change', filterTable);
    if (filterCondition) filterCondition.addEventListener('change', filterTable);

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Simplified and modern styles */
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

.btn-sm i {
    font-size: 0.8rem;
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Hover effects */
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.btn:hover {
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

/* Responsive styles */
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
    
    .table th, .table td {
        padding: 0.75rem 0.5rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.3rem 0.5rem;
    }
}

/* Additional styles */
.modal-body {
    max-height: calc(100vh - 210px);
    overflow-y: auto;
}

.text-primary-custom {
    color: #5e72e4;
}

.bg-gradient-primary {
    background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%);
}
</style>
@endpush