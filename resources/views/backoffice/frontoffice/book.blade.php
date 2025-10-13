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
                            <h5 class="mb-1 fw-bold text-dark">📚 Gestion des Livres</h5>
                            <p class="text-muted mb-0">Gérez votre collection de livres partagés</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2">
                                <i class="fa-solid fa-tags fa-fw"></i>
                                Catégories
                            </a>
                            <a href="{{ route('books.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                                <i class="fa-solid fa-plus fa-fw"></i>
                                Ajouter un livre
                            </a>
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
                                    {{ method_exists($books, 'total') ? $books->total() : $books->count() }}
                                </h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="border-end border-gray-300 pe-3">
                                <h6 class="text-sm text-muted mb-1">Disponibles</h6>
                                <h4 class="fw-bold mb-0 text-success">{{ $books->where('availability', true)->count() }}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="border-end border-gray-300 pe-3">
                                <h6 class="text-sm text-muted mb-1">Avec PDF</h6>
                                <h4 class="fw-bold mb-0 text-primary">{{ $books->whereNotNull('file')->count() }}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div>
                                <h6 class="text-sm text-muted mb-1">Catégories</h6>
                                <h4 class="fw-bold mb-0 text-warning">{{ $books->pluck('category_id')->unique()->count() }}</h4>
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
                                    <select class="form-select form-select-sm w-auto border">
                                        <option>Tous les statuts</option>
                                        <option>Disponible</option>
                                        <option>Indisponible</option>
                                    </select>
                                    <select class="form-select form-select-sm w-auto border">
                                        <option>Toutes les catégories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-items-center mb-0" id="booksTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 text-uppercase text-muted text-xs fw-bold">Couverture</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold">Livre</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold">Auteur</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold">Catégorie</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold text-center">État</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold text-center">Statut</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($books as $book)
                                <tr class="border-bottom">
                                    <!-- Image -->
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xl">
                                                @if($book->image)
                                                    <img src="{{ asset('storage/' . $book->image) }}" 
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
                                                {{ $book->publication_year ? date('Y', strtotime($book->publication_year)) : 'N/A' }}
                                            </p>
                                        </div>
                                    </td>

                                    <!-- Auteur -->
                                    <td>
                                        <span class="text-sm text-dark">
                                            {{ Str::limit($book->author, 20) }}
                                        </span>
                                    </td>

                                    <!-- Catégorie -->
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $book->category ? $book->category->name : 'Non catégorisé' }}
                                        </span>
                                    </td>

                                    <!-- État -->
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark">
                                            {{ $book->condition }}
                                        </span>
                                    </td>

                                    <!-- Disponibilité -->
                                    <td class="text-center">
                                        <span class="badge {{ $book->availability ? 'bg-success' : 'bg-danger' }} text-white">
                                            {{ $book->availability ? 'Disponible' : 'Indisponible' }}
                                        </span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Modifier -->
                                            <a href="{{ route('books.edit', $book->id) }}" 
                                               class="btn btn-sm btn-outline-primary rounded"
                                               data-bs-toggle="tooltip" 
                                               data-bs-title="Modifier">
                                                <span title="Edit" style="font-size:1.1em;">✏️</span>
                                            </a>

                                            <!-- Supprimer -->
                                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger rounded"
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-title="Supprimer"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')">
                                                    <span title="Delete" style="font-size:1.1em;">🗑️</span>
                                                </button>
                                            </form>

                                            <!-- Voir PDF -->
                                            @if($book->file)
                                                <button class="btn btn-sm btn-outline-info rounded"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#pdfModal{{ $book->id }}"
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-title="Voir PDF">
                                                    <span title="PDF" style="font-size:1.1em;">📄</span>
                                                </button>
                                            @endif

                                            <!-- View details -->
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $book->id }}" title="Details">
                                                <span title="View" style="font-size:1.1em;">👁️</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- PDF Modal -->
                                @if($book->file)
                                <div class="modal fade" id="pdfModal{{ $book->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h6 class="modal-title text-white">
                                                    <span title="PDF" style="font-size:1.1em;">📄</span>
                                                    {{ $book->title }}
                                                </h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-0">
                                                <iframe src="{{ asset('storage/' . $book->file) }}#toolbar=0" 
                                                        width="100%" 
                                                        height="600" 
                                                        style="border: none;">
                                                </iframe>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{ asset('storage/' . $book->file) }}" 
                                                   download 
                                                   class="btn btn-sm btn-primary">
                                                    <span title="Download" style="font-size:1.1em;">⬇️</span>
                                                    Download
                                                </a>
                                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <!-- Book Details Modal -->
                                <div class="modal fade" id="detailsModal{{ $book->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $book->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="detailsModalLabel{{ $book->id }}">Book Details</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-4 text-center mb-3 mb-md-0">
                                                        @if($book->image)
                                                            <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}" class="img-fluid rounded shadow" style="max-height: 220px;">
                                                        @else
                                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 120px; height: 170px; margin: 0 auto;">
                                                                <span style="font-size:2.5em; color: #fff;">📚</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-8">
                                                        <h5 class="fw-bold mb-2">{{ $book->title }}</h5>
                                                        <p class="mb-1"><strong>Author:</strong> {{ $book->author }}</p>
                                                        <p class="mb-1"><strong>Category:</strong> {{ $book->category ? $book->category->name : 'Uncategorized' }}</p>
                                                        <p class="mb-1"><strong>Year:</strong> {{ $book->publication_year ? date('Y', strtotime($book->publication_year)) : 'N/A' }}</p>
                                                        <p class="mb-1"><strong>Condition:</strong> {{ $book->condition }}</p>
                                                        <p class="mb-1"><strong>Status:</strong> <span class="badge {{ $book->availability ? 'bg-success' : 'bg-danger' }} text-white">{{ $book->availability ? 'Available' : 'Unavailable' }}</span></p>
                                                        @if($book->description)
                                                        <hr>
                                                        <p class="mb-0"><strong>Description:</strong><br>{{ $book->description }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($books, 'hasPages') && $books->hasPages())
                    <div class="card-footer bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="text-sm text-muted mb-0">
                                Showing {{ $books->firstItem() }} to {{ $books->lastItem() }} of {{ $books->total() }} books
                            </p>
                            <nav aria-label="Page navigation">
                                {{ $books->links('vendor.pagination.bootstrap-5') }}
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
// Real-time search
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchValue = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#booksTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });
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

/* Simplified colors */
.bg-light {
    background-color: #f8f9fa !important;
}

.text-muted {
    color: #6c757d !important;
}

/* Hover effects */
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

.fa-sm {
    font-size: 0.875em;
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
    
    .table th, .table td {
        padding: 0.75rem 0.5rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.3rem 0.5rem;
    }
    
    .btn-sm i {
        font-size: 0.7rem;
        width: 14px;
        height: 14px;
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