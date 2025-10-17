@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête du défi -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-gradient-{{ $challenge->getDifficultyColor() }} text-white position-relative">
                    <!-- Badges de statut -->
                    <div class="position-absolute top-0 end-0 mt-3 me-3">
                        @if($challenge->is_ai_generated)
                            <span class="badge bg-white text-dark me-2">
                                <i class="fas fa-magic me-1"></i>Généré par IA
                            </span>
                        @endif
                        <span class="badge bg-{{ $challenge->status === 'active' ? 'success' : 'secondary' }}">
                            {{ $challenge->status === 'active' ? 'Actif' : 'Terminé' }}
                        </span>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                <a href="{{ route('challenges.index', $group) }}" class="text-white me-3">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                <h3 class="mb-0">{{ $challenge->title }}</h3>
                            </div>
                            <div class="d-flex align-items-center text-sm opacity-8">
                                <i class="fas fa-{{ $challenge->getTypeIcon() }} me-2"></i>
                                <span class="me-3">{{ $challenge->getTypeLabel() }}</span>
                                <i class="fas fa-layer-group me-2"></i>
                                <span class="me-3">Difficulté : {{ ucfirst($challenge->difficulty_level) }}</span>
                                <i class="fas fa-users me-2"></i>
                                <span>Groupe : {{ $group->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="text-white">
                                <div class="h4 mb-0">{{ $stats['days_remaining'] }}</div>
                                <small class="opacity-8">jours restants</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <h5>Description du Défi</h5>
                            <p class="text-muted mb-4">{{ $challenge->description }}</p>
                            
                            <!-- Objectifs -->
                            @if($challenge->objectives)
                                <h5>Objectifs à Atteindre</h5>
                                <div class="row">
                                    @foreach($challenge->objectives as $key => $value)
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="icon icon-sm bg-gradient-success shadow text-center rounded-circle me-3">
                                                    <i class="fas fa-target text-white"></i>
                                                </div>
                                                <div>
                                                    @if($key === 'target_books')
                                                        <h6 class="mb-0">{{ $value }} Livre(s)</h6>
                                                        <span class="text-xs text-muted">à lire pendant le défi</span>
                                                    @elseif($key === 'target_pages')
                                                        <h6 class="mb-0">{{ number_format($value) }} Pages</h6>
                                                        <span class="text-xs text-muted">au total</span>
                                                    @else
                                                        <h6 class="mb-0">{{ ucfirst(str_replace('_', ' ', $key)) }}</h6>
                                                        <span class="text-xs text-muted">requis</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Récompenses -->
                            @if($challenge->rewards)
                                <h5 class="mt-4">Récompenses</h5>
                                <div class="row">
                                    @if(isset($challenge->rewards['points']))
                                        <div class="col-md-4 mb-3">
                                            <div class="text-center">
                                                <div class="icon icon-lg bg-gradient-warning shadow mx-auto">
                                                    <i class="fas fa-coins text-white"></i>
                                                </div>
                                                <h5 class="mt-2">{{ $challenge->rewards['points'] }} Points</h5>
                                            </div>
                                        </div>
                                    @endif
                                    @if(isset($challenge->rewards['badge']))
                                        <div class="col-md-4 mb-3">
                                            <div class="text-center">
                                                <div class="icon icon-lg bg-gradient-info shadow mx-auto">
                                                    <i class="fas fa-medal text-white"></i>
                                                </div>
                                                <h5 class="mt-2">Badge</h5>
                                                <p class="text-sm text-muted">{{ $challenge->rewards['badge'] }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    @if(isset($challenge->rewards['certificate']))
                                        <div class="col-md-4 mb-3">
                                            <div class="text-center">
                                                <div class="icon icon-lg bg-gradient-success shadow mx-auto">
                                                    <i class="fas fa-certificate text-white"></i>
                                                </div>
                                                <h5 class="mt-2">Certificat</h5>
                                                <p class="text-sm text-muted">de réussite</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="col-lg-4">
                            <!-- Statistiques -->
                            <div class="card bg-gradient-light">
                                <div class="card-body">
                                    <h6 class="mb-3">Statistiques du Défi</h6>
                                    
                                    <div class="row text-center mb-3">
                                        <div class="col-6">
                                            <h4 class="text-primary">{{ $stats['total_participants'] }}</h4>
                                            <span class="text-xs">Participants</span>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-success">{{ $stats['completed_participants'] }}</h4>
                                            <span class="text-xs">Terminé</span>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span class="text-sm">Progression Moyenne</span>
                                            <span class="text-sm font-weight-bold">{{ $stats['average_progress'] }}%</span>
                                        </div>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-gradient-info" style="width: {{ $stats['average_progress'] }}%"></div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <span class="text-sm text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            Du {{ $challenge->start_date->format('d/m/Y') }} au {{ $challenge->end_date->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions utilisateur -->
                            <div class="mt-4">
                                @if(!$userParticipation && $challenge->status === 'active')
                                    <form action="{{ route('challenges.join', [$group, $challenge]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                            <i class="fas fa-plus me-2"></i>
                                            Rejoindre le Défi
                                        </button>
                                    </form>
                                @elseif($userParticipation)
                                    <!-- Progression utilisateur -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Votre Progression</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center mb-3">
                                                <div class="position-relative d-inline-block">
                                                    <canvas id="progressChart" width="120" height="120"></canvas>
                                                    <div class="position-absolute top-50 start-50 translate-middle">
                                                        <h4 class="mb-0">{{ $userParticipation->progress_percentage }}%</h4>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($userParticipation->status === 'completed')
                                                <div class="alert alert-success text-center">
                                                    <i class="fas fa-trophy fa-2x mb-2"></i>
                                                    <h6>Défi Terminé !</h6>
                                                    <small>Félicitations, vous avez relevé le défi !</small>
                                                </div>
                                            @else
                                                <button type="button" class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#progressModal">
                                                    <i class="fas fa-chart-line me-2"></i>
                                                    Mettre à jour
                                                </button>
                                                <form action="{{ route('challenges.leave', [$group, $challenge]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir quitter ce défi ?')">
                                                        <i class="fas fa-times me-1"></i>
                                                        Quitter le Défi
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Classement des participants -->
    @if($stats['leaderboard']->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-trophy me-2"></i>
                        Classement des Participants
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rang</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Participant</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Progression</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dernière MàJ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['leaderboard'] as $index => $participant)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($index === 0)
                                                <i class="fas fa-crown text-warning me-2"></i>
                                            @elseif($index === 1)
                                                <i class="fas fa-medal text-muted me-2"></i>
                                            @elseif($index === 2)
                                                <i class="fas fa-medal text-warning me-2"></i>
                                            @else
                                                <span class="me-3">{{ $index + 1 }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <img src="{{ $participant->user->profile_image ?? 'https://ui-avatars.com/api/?name=' . urlencode($participant->user->name) }}" 
                                                     class="rounded-circle" alt="{{ $participant->user->name }}">
                                            </div>
                                            <span class="font-weight-bold">{{ $participant->user->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-2">
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar bg-gradient-{{ $participant->progress_percentage >= 100 ? 'success' : 'info' }}" 
                                                         style="width: {{ $participant->progress_percentage }}%"></div>
                                                </div>
                                            </div>
                                            <span class="text-sm font-weight-bold">{{ $participant->progress_percentage }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($participant->status === 'completed')
                                            <span class="badge bg-success">Terminé</span>
                                        @else
                                            <span class="badge bg-info">En cours</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-sm text-muted">
                                            {{ $participant->last_update ? $participant->last_update->diffForHumans() : 'Jamais' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal de mise à jour de progression -->
@if($userParticipation && $userParticipation->status !== 'completed')
<div class="modal fade" id="progressModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-chart-line me-2"></i>
                    Mettre à jour ma progression
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('challenges.progress', [$group, $challenge]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Type de progression</label>
                                <select name="progress_type" class="form-select" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="book_completed">Livre terminé</option>
                                    <option value="pages_read">Pages lues</option>
                                    <option value="book_started">Livre commencé</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Valeur</label>
                                <input type="number" name="value" class="form-control" min="0" required>
                                <small class="text-muted">Ex: 1 pour un livre, 50 pour des pages</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Titre du livre</label>
                                <input type="text" name="book_title" class="form-control" maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Auteur</label>
                                <input type="text" name="author" class="form-control" maxlength="255">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Pages lues (optionnel)</label>
                        <input type="number" name="pages_read" class="form-control" min="0">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notes/Commentaires</label>
                        <textarea name="notes" class="form-control" rows="3" maxlength="1000" 
                                  placeholder="Vos impressions, citations préférées, etc."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique de progression circulaire
    @if($userParticipation)
    const canvas = document.getElementById('progressChart');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        const progress = {{ $userParticipation->progress_percentage }};
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = 45;
        
        // Cercle de fond
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
        ctx.strokeStyle = '#e9ecef';
        ctx.lineWidth = 8;
        ctx.stroke();
        
        // Cercle de progression
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, -Math.PI / 2, (-Math.PI / 2) + (2 * Math.PI * progress / 100));
        ctx.strokeStyle = progress >= 100 ? '#28a745' : '#007bff';
        ctx.lineWidth = 8;
        ctx.lineCap = 'round';
        ctx.stroke();
    }
    @endif
});
</script>

<style>
.icon {
    width: 48px;
    height: 48px;
}

.icon-sm {
    width: 32px;
    height: 32px;
}

.icon-lg {
    width: 64px;
    height: 64px;
}

.icon i {
    font-size: 1.5rem;
}

.icon-sm i {
    font-size: 1rem;
}

.icon-lg i {
    font-size: 2rem;
}

.progress-sm {
    height: 6px;
}

.avatar {
    width: 40px;
    height: 40px;
}

.avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
@endsection