@extends('frontoffice.layouts.app')
@section('title', 'Badges - ' . $group->name . ' - Bookly')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-6">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('frontoffice.group.wall', $group->id) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                    ← Retour au groupe
                </a>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                    <span class="text-white text-2xl">🏆</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Badges du groupe {{ $group->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400">Récompenses et reconnaissance des membres actifs</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Classement -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="text-2xl">🏅</span>
                        Classement des membres
                    </h2>
                    
                    <div class="space-y-4">
                        @forelse($leaderboard as $index => $member)
                        <div class="flex items-center gap-4 p-4 rounded-xl {{ $index < 3 ? 'bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border border-yellow-200 dark:border-yellow-700' : 'bg-gray-50 dark:bg-gray-700' }}">
                            <div class="text-2xl">
                                @if($index === 0) 🥇
                                @elseif($index === 1) 🥈
                                @elseif($index === 2) 🥉
                                @else <span class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center text-sm font-bold">{{ $index + 1 }}</span>
                                @endif
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                {{ substr($member->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $member->user->name }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $member->total_points }} points • {{ $member->badges_count }} badge(s)
                                </div>
                            </div>
                            <button class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium"
                                    onclick="showMemberBadges({{ $member->user_id }}, '{{ $member->user->name }}')">
                                Voir badges
                            </button>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <div class="text-4xl mb-2">🏆</div>
                            <p>Aucun badge attribué pour le moment</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Types de badges -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="text-xl">🎖️</span>
                        Types de badges
                    </h3>
                    
                    <div class="space-y-3">
                        @foreach($badgeTypes as $type => $details)
                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-700">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-lg">{{ $details['icon'] }}</span>
                                <span class="font-medium text-sm" style="color: {{ $details['color'] }}">
                                    {{ $details['name'] }}
                                </span>
                                <span class="text-xs bg-gray-200 dark:bg-gray-600 px-2 py-0.5 rounded-full">
                                    {{ $details['points'] }} pts
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $details['description'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Badges récents -->
                @if($recentBadges->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="text-xl">⭐</span>
                        Badges récents
                    </h3>
                    
                    <div class="space-y-3">
                        @foreach($recentBadges as $badge)
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">{{ $badge->badge_icon }}</span>
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-sm text-gray-900 dark:text-white truncate">
                                    {{ $badge->user->name }}
                                </div>
                                <div class="text-xs" style="color: {{ $badge->badge_color }}">
                                    {{ $badge->badge_name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $badge->earned_date->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les badges d'un membre -->
<div id="memberBadgesModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full max-h-[80vh] overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="modalTitle">Badges de membre</h3>
                    <button onclick="closeMemberBadgesModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <span class="text-2xl">×</span>
                    </button>
                </div>
            </div>
            <div class="p-6 overflow-y-auto" id="modalContent">
                <!-- Contenu dynamique -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function showMemberBadges(userId, userName) {
    document.getElementById('modalTitle').textContent = `Badges de ${userName}`;
    document.getElementById('modalContent').innerHTML = '<div class="text-center py-4">Chargement...</div>';
    document.getElementById('memberBadgesModal').classList.remove('hidden');
    
    try {
        const response = await fetch(`/groups/{{ $group->id }}/members/${userId}/badges`);
        const data = await response.json();
        
        let content = '';
        
        if (data.badges && data.badges.length > 0) {
            content = `
                <div class="text-center mb-4">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">${data.total_points}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Points totaux</div>
                </div>
                <div class="space-y-3">
            `;
            
            data.badges.forEach(badge => {
                content += `
                    <div class="p-4 rounded-xl bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border border-yellow-200 dark:border-yellow-700">
                        <div class="flex items-center gap-3">
                            <span class="text-3xl">${badge.badge_icon}</span>
                            <div class="flex-1">
                                <div class="font-semibold" style="color: ${badge.badge_color}">${badge.badge_name}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">${badge.badge_description}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Obtenu le ${new Date(badge.earned_date).toLocaleDateString('fr-FR')} • ${badge.points_earned} points
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            content += '</div>';
        } else {
            content = `
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <div class="text-4xl mb-2">🏆</div>
                    <p>Aucun badge obtenu pour le moment</p>
                </div>
            `;
        }
        
        document.getElementById('modalContent').innerHTML = content;
    } catch (error) {
        document.getElementById('modalContent').innerHTML = `
            <div class="text-center py-8 text-red-500">
                <div class="text-4xl mb-2">❌</div>
                <p>Erreur lors du chargement des badges</p>
            </div>
        `;
    }
}

function closeMemberBadgesModal() {
    document.getElementById('memberBadgesModal').classList.add('hidden');
}

// Fermer la modal en cliquant à l'extérieur
document.getElementById('memberBadgesModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMemberBadgesModal();
    }
});
</script>
@endpush