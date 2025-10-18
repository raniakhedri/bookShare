@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Mon Tableau de Bord - Défis de Lecture
                    </h4>
                    <p class="mb-0 opacity-8">Suivez vos performances et découvrez vos statistiques</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Défis Totaux</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $stats['total_challenges'] }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-trophy text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Défis Actifs</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $stats['active_challenges'] }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="fas fa-play text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Défis Terminés</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $stats['completed_challenges'] }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="fas fa-check-circle text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Points Gagnés</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ number_format($stats['total_points']) }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                <i class="fas fa-coins text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progression générale -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Progression Générale</h6>
                    <p class="text-sm mb-0">Taux de completion moyen de vos défis actifs</p>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-3" style="height: 20px;">
                                    <div class="progress-bar bg-gradient-info progress-bar-striped progress-bar-animated" 
                                         style="width: {{ $stats['completion_rate'] }}%"></div>
                                </div>
                                <span class="font-weight-bold">{{ $stats['completion_rate'] }}%</span>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            @if($stats['completion_rate'] >= 80)
                                <span class="badge bg-success">Excellent</span>
                            @elseif($stats['completion_rate'] >= 60)
                                <span class="badge bg-warning">Bien</span>
                            @elseif($stats['completion_rate'] >= 40)
                                <span class="badge bg-info">Moyen</span>
                            @else
                                <span class="badge bg-secondary">À améliorer</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Défis actifs -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-fire text-warning me-2"></i>
                        Mes Défis Actifs
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($activeParticipations as $participation)
                        <div class="d-flex align-items-center p-3 border rounded mb-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar bg-gradient-{{ $participation->challenge->getDifficultyColor() }}">
                                    <i class="fas fa-{{ $participation->challenge->getTypeIcon() }} text-white"></i>
                                </div>
                            </div>
                            
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $participation->challenge->title }}</h6>
                                <p class="text-sm text-muted mb-2">
                                    {{ $participation->challenge->group->name }} • 
                                    {{ $participation->challenge->end_date->diffForHumans() }}
                                </p>
                                
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-gradient-{{ $participation->progress_percentage >= 100 ? 'success' : 'info' }}" 
                                                 style="width: {{ $participation->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <span class="text-sm font-weight-bold">{{ $participation->progress_percentage }}%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex-shrink-0 ms-3">
                                <a href="{{ route('challenges.show', [$participation->challenge->group, $participation->challenge]) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>
                                    Voir
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-trophy fa-3x text-muted opacity-5 mb-3"></i>
                            <h6 class="text-muted">Aucun défi actif</h6>
                            <p class="text-muted">Rejoignez des groupes et participez à leurs défis !</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Achievements récents -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-medal text-warning me-2"></i>
                        Achievements Récents
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($completedParticipations->take(5) as $participation)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="icon icon-sm bg-gradient-success shadow text-center rounded-circle">
                                    <i class="fas fa-trophy text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-sm">{{ $participation->challenge->title }}</h6>
                                <p class="text-xs text-muted mb-0">
                                    {{ $participation->completed_at->format('d/m/Y') }}
                                </p>
                                @if(isset($participation->challenge->rewards['points']))
                                    <span class="badge bg-warning text-dark">+{{ $participation->challenge->rewards['points'] }} pts</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3">
                            <i class="fas fa-medal fa-2x text-muted opacity-5 mb-2"></i>
                            <p class="text-muted text-sm">Aucun défi terminé</p>
                        </div>
                    @endforelse

                    @if($completedParticipations->count() > 5)
                        <div class="text-center mt-3">
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#achievementsModal">
                                Voir tous les achievements
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Conseils IA -->
            <div class="card mt-4">
                <div class="card-header bg-gradient-info text-white">
                    <h6 class="mb-0 text-white">
                        <i class="fas fa-lightbulb me-2"></i>
                        Conseil du Jour
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $tips = [
                            "Fixez-vous des objectifs de lecture quotidiens pour maintenir votre rythme !",
                            "Diversifiez vos genres littéraires pour enrichir votre culture.",
                            "Partagez vos impressions avec votre groupe pour créer des discussions enrichissantes.",
                            "Tenez un journal de lecture pour suivre vos progrès et réflexions.",
                            "N'hésitez pas à participer à plusieurs défis simultanément !"
                        ];
                        $dailyTip = $tips[array_rand($tips)];
                    @endphp
                    
                    <p class="text-sm mb-2">{{ $dailyTip }}</p>
                    <small class="text-muted">
                        <i class="fas fa-robot me-1"></i>
                        Conseil généré par IA
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal des achievements complets -->
@if($completedParticipations->count() > 5)
<div class="modal fade" id="achievementsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-trophy me-2"></i>
                    Tous mes Achievements
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @foreach($completedParticipations as $participation)
                        <div class="col-md-6 mb-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-sm bg-gradient-success shadow text-center rounded-circle me-3">
                                            <i class="fas fa-trophy text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $participation->challenge->title }}</h6>
                                            <p class="text-xs text-muted mb-1">{{ $participation->challenge->group->name }}</p>
                                            <p class="text-xs text-success mb-0">
                                                Terminé le {{ $participation->completed_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                        @if(isset($participation->challenge->rewards['points']))
                                            <span class="badge bg-warning">+{{ $participation->challenge->rewards['points'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<style>
.icon {
    width: 48px;
    height: 48px;
}

.icon-sm {
    width: 32px;
    height: 32px;
}

.icon i {
    font-size: 1.5rem;
}

.icon-sm i {
    font-size: 1rem;
}

.progress-sm {
    height: 6px;
}

.avatar {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.progress-bar-striped {
    background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-size: 1rem 1rem;
}

.progress-bar-animated {
    animation: progress-bar-stripes 1s linear infinite;
}

@keyframes progress-bar-stripes {
    0% {
        background-position-x: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des cartes de statistiques
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'opacity 0.3s, transform 0.3s';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Animation de la barre de progression
    const progressBar = document.querySelector('.progress-bar-animated');
    if (progressBar) {
        setTimeout(() => {
            progressBar.style.width = '{{ $stats["completion_rate"] }}%';
        }, 500);
    }
});
</script>
@endsection