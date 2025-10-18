@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête avec actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="fas fa-trophy me-2"></i>
                            Défis de Lecture - {{ $group->name }}
                        </h4>
                        <p class="mb-0 opacity-8">{{ $group->category->name ?? 'Catégorie générale' }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#generateChallengeModal">
                            <i class="fas fa-magic me-1"></i>
                            Générer un Défi IA
                        </button>
                        <a href="{{ route('challenges.dashboard') }}" class="btn btn-outline-white btn-sm">
                            <i class="fas fa-chart-line me-1"></i>
                            Mon Tableau de Bord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mt-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="icon icon-shape bg-gradient-success shadow mx-auto mb-3">
                        <i class="fas fa-flag-checkered text-white"></i>
                    </div>
                    <h5 class="text-success">{{ $challenges->where('status', 'active')->count() }}</h5>
                    <span class="text-sm">Défis Actifs</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="icon icon-shape bg-gradient-info shadow mx-auto mb-3">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <h5 class="text-info">{{ $challenges->sum('current_participants') }}</h5>
                    <span class="text-sm">Participants Total</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="icon icon-shape bg-gradient-warning shadow mx-auto mb-3">
                        <i class="fas fa-crown text-white"></i>
                    </div>
                    <h5 class="text-warning">{{ $challenges->where('status', 'completed')->count() }}</h5>
                    <span class="text-sm">Défis Terminés</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="icon icon-shape bg-gradient-secondary shadow mx-auto mb-3">
                        <i class="fas fa-robot text-white"></i>
                    </div>
                    <h5 class="text-secondary">{{ $challenges->where('is_ai_generated', true)->count() }}</h5>
                    <span class="text-sm">Défis IA</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des défis -->
    <div class="row mt-4">
        @forelse($challenges as $challenge)
        <div class="col-lg-6 col-xl-4 mb-4">
            <div class="card h-100 challenge-card" data-status="{{ $challenge->status }}">
                <!-- Badge de statut -->
                <div class="position-relative">
                    @if($challenge->status === 'active')
                        <span class="badge bg-success position-absolute top-0 start-0 m-3 z-index-1">
                            <i class="fas fa-play me-1"></i>Actif
                        </span>
                    @elseif($challenge->status === 'completed')
                        <span class="badge bg-secondary position-absolute top-0 start-0 m-3 z-index-1">
                            <i class="fas fa-check me-1"></i>Terminé
                        </span>
                    @endif

                    @if($challenge->is_ai_generated)
                        <span class="badge bg-gradient-primary position-absolute top-0 end-0 m-3 z-index-1">
                            <i class="fas fa-magic me-1"></i>IA
                        </span>
                    @endif
                </div>

                <!-- En-tête du défi -->
                <div class="card-header bg-gradient-{{ $challenge->getDifficultyColor() }} text-white">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $challenge->title }}</h6>
                            <div class="d-flex align-items-center text-sm opacity-8">
                                <i class="fas fa-{{ $challenge->getTypeIcon() }} me-1"></i>
                                <span>{{ $challenge->getTypeLabel() }}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-layer-group me-1"></i>
                                <span>{{ ucfirst($challenge->difficulty_level) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Description -->
                    <p class="text-sm text-muted mb-3">
                        {{ Str::limit($challenge->description, 120) }}
                    </p>

                    <!-- Progression si participant -->
                    @if(isset($userParticipations[$challenge->id]))
                        @php $participation = $userParticipations[$challenge->id] @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-sm font-weight-bold">Votre Progression</span>
                                <span class="text-sm">{{ $participation->progress_percentage }}%</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-gradient-{{ $participation->progress_percentage >= 100 ? 'success' : 'info' }}" 
                                     style="width: {{ $participation->progress_percentage }}%"></div>
                            </div>
                            @if($participation->status === 'completed')
                                <small class="text-success">
                                    <i class="fas fa-trophy me-1"></i>Défi terminé !
                                </small>
                            @endif
                        </div>
                    @endif

                    <!-- Informations du défi -->
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="text-primary mb-0">{{ $challenge->current_participants }}</h6>
                                <span class="text-xs text-muted">Participants</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-info mb-0">{{ $challenge->getDaysRemaining() }}</h6>
                            <span class="text-xs text-muted">Jours restants</span>
                        </div>
                    </div>

                    <!-- Objectifs -->
                    @if($challenge->objectives)
                        <div class="mb-3">
                            <h6 class="text-sm font-weight-bold mb-2">Objectifs :</h6>
                            <ul class="list-unstyled mb-0">
                                @foreach($challenge->objectives as $key => $value)
                                    <li class="text-xs text-muted">
                                        <i class="fas fa-check-circle text-success me-1"></i>
                                        @if($key === 'target_books')
                                            Lire {{ $value }} livre(s)
                                        @elseif($key === 'target_pages')
                                            Lire {{ $value }} pages
                                        @else
                                            {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $challenge->end_date->format('d/m/Y') }}
                        </small>
                        
                        <div class="btn-group" role="group">
                            <a href="{{ route('challenges.show', [$group, $challenge]) }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>Voir
                            </a>
                            
                            @if(!isset($userParticipations[$challenge->id]) && $challenge->status === 'active')
                                <form action="{{ route('challenges.join', [$group, $challenge]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-1"></i>Rejoindre
                                    </button>
                                </form>
                            @elseif(isset($userParticipations[$challenge->id]) && $userParticipations[$challenge->id]->status === 'active')
                                <a href="{{ route('challenges.show', [$group, $challenge]) }}" 
                                   class="btn btn-success btn-sm">
                                    <i class="fas fa-chart-line me-1"></i>Progression
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-trophy fa-4x text-muted opacity-5"></i>
                    </div>
                    <h5 class="text-muted">Aucun défi disponible</h5>
                    <p class="text-muted mb-4">
                        Ce groupe n'a pas encore de défis de lecture. 
                        Générez un défi personnalisé avec l'IA !
                    </p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateChallengeModal">
                        <i class="fas fa-magic me-2"></i>
                        Créer le Premier Défi
                    </button>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal de génération de défi -->
<div class="modal fade" id="generateChallengeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-magic me-2"></i>
                    Générer un Défi IA
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('challenges.generate', $group) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb me-2"></i>
                        L'IA va analyser les habitudes de lecture de votre groupe et générer un défi personnalisé 
                        basé sur la catégorie <strong>{{ $group->category->name ?? 'Général' }}</strong>.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type de défi (optionnel)</label>
                        <select name="challenge_type" class="form-select">
                            <option value="">Laisser l'IA choisir</option>
                            <option value="monthly_genre">Exploration de Genre</option>
                            <option value="author_focus">Focus Auteur</option>
                            <option value="cultural_discovery">Découverte Culturelle</option>
                            <option value="page_challenge">Défi de Pages</option>
                            <option value="speed_reading">Lecture Rapide</option>
                            <option value="classic_revival">Renaissance Classique</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Niveau de difficulté (optionnel)</label>
                        <select name="difficulty" class="form-select">
                            <option value="">Laisser l'IA choisir</option>
                            <option value="easy">Facile (1-2 livres)</option>
                            <option value="medium">Moyen (2-3 livres)</option>
                            <option value="hard">Difficile (3+ livres)</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-magic me-1"></i>
                        Générer le Défi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.challenge-card {
    transition: transform 0.2s;
}

.challenge-card:hover {
    transform: translateY(-5px);
}

.icon {
    width: 48px;
    height: 48px;
}

.icon i {
    font-size: 1.5rem;
}

.progress-sm {
    height: 6px;
}

.z-index-1 {
    z-index: 1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'apparition des cartes
    const cards = document.querySelectorAll('.challenge-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'opacity 0.3s, transform 0.3s';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection