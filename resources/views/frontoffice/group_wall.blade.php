@extends('frontoffice.layouts.app')
@section('title', $group->name . ' - Bookly')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

<!-- Toast Success Message -->
@if(session('success'))
    <div id="toast-success" class="fixed z-50 right-6 bottom-6 bg-green-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 animate-fade-in-up" style="min-width:220px;">
        <span class="text-xl">✅</span>
        <span>{{ session('success') }}</span>
    </div>
    <script>
        setTimeout(function() {
            var toast = document.getElementById('toast-success');
            if (toast) toast.style.display = 'none';
        }, 3500);
    </script>
    <style>
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.5s; }
    </style>
@endif

<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-6">
    <div class="container mx-auto px-4 max-w-6xl flex flex-col md:flex-row gap-8">
        <!-- Colonne gauche : Infos groupe -->
        <aside class="md:w-1/3 w-full flex flex-col gap-6 order-2 md:order-1">
            <!-- Header du groupe modernisé -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6 flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-[#f87171] to-[#fca5a5] rounded-full flex items-center justify-center mb-3 overflow-hidden shadow-md">
                    @if($group->image)
                        <img src="{{ asset('storage/'.$group->image) }}" alt="Image du groupe" class="w-full h-full object-cover rounded-full">
                    @else
                        <span class="text-white text-3xl">👥</span>
                    @endif
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $group->name }}</h1>
                <div class="flex flex-wrap gap-2 justify-center mb-2">
                    <span class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 text-xs px-3 py-1 rounded-full font-medium">{{ $group->theme }}</span>
                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs px-3 py-1 rounded-full">{{ $group->is_public ? 'Public' : 'Privé' }}</span>
                </div>
                <p class="text-gray-700 dark:text-gray-300 text-sm mb-4 leading-relaxed">{{ $group->description }}</p>
                <div class="flex flex-col gap-2 text-sm text-gray-600 dark:text-gray-400 items-center w-full">
                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-full font-bold text-xs">A</span>
                        <span>Créé par {{ $group->creator->name ?? 'Administrateur' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="bg-gray-100 dark:bg-gray-700 p-1 rounded-full">📅</span>
                        <span>Créé le {{ $group->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-center gap-3 mt-2 w-full">
                        <span class="bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 text-xs px-3 py-1 rounded-full font-bold">{{ $memberCount }} membres</span>
                        <span class="bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-xs px-3 py-1 rounded-full font-bold">{{ $posts->count() }} publications</span>
                    </div>
                </div>
            </div>
            
            <!-- Membres récents -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <span class="bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 p-1 rounded-full">👥</span>
                    Membres récents
                </h3>
                <div class="grid grid-cols-4 gap-3 mb-3">
                    @foreach($recentMembers as $member)
                        <div class="text-center group cursor-pointer">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#f87171] to-[#fca5a5] rounded-full mx-auto mb-1 flex items-center justify-center text-white text-sm font-bold shadow-md group-hover:shadow-lg transition-all">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            <div class="flex items-center justify-center gap-1">                                
                                <span class="text-xs text-gray-600 dark:text-gray-400 truncate block group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ explode(' ', $member->name)[0] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="flex gap-2">
                    <button class="flex-1 text-center text-red-900 dark:text-red-400 text-sm font-medium py-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors flex items-center justify-center gap-1">
                        Tous les membres
                        <span>→</span>
                    </button>
                    <a href="{{ route('groups.badges', $group->id) }}" class="flex-1 text-center text-yellow-600 dark:text-yellow-400 text-sm font-medium py-2 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-colors flex items-center justify-center gap-1">
                        🏆 Badges
                    </a>
                </div>
            </div>
            
            <!-- Mes badges -->
            @if($userBadges->count() > 0)
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-2xl shadow-md border border-yellow-200 dark:border-yellow-700 p-4">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <span class="bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 p-1 rounded-full">🏆</span>
                    Mes badges
                </h3>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($userBadges->take(4) as $badge)
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                            <div class="text-2xl mb-1">{{ $badge->badge_icon }}</div>
                            <div class="text-xs font-semibold text-gray-900 dark:text-white">{{ $badge->badge_name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $badge->points_earned }} pts</div>
                        </div>
                    @endforeach
                </div>
                @if($userBadges->count() > 4)
                    <a href="{{ route('groups.badges', $group->id) }}" class="w-full text-center text-yellow-600 dark:text-yellow-400 text-sm font-medium py-2 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-colors flex items-center justify-center gap-1 mt-2">
                        Voir tous mes badges ({{ $userBadges->count() }})
                        <span>→</span>
                    </a>
                @endif
            </div>
            @endif

            <!-- Top contributeurs -->
            @if($topContributors->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <span class="bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 p-1 rounded-full">🌟</span>
                    Top contributeurs
                </h3>
                <div class="space-y-3">
                    @foreach($topContributors as $index => $contributor)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-[#f87171] to-[#fca5a5] rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md">
                                {{ substr($contributor->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-sm text-gray-900 dark:text-white">{{ $contributor->user->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $contributor->total_points }} pts • {{ $contributor->badges_count }} badge(s)</div>
                            </div>
                            <div class="text-lg">
                                @if($index === 0) 🥇
                                @elseif($index === 1) 🥈
                                @elseif($index === 2) 🥉
                                @else ⭐
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Défis de Lecture Actifs -->
            @php
                $activeChallenges = $group->activeChallenges()->limit(2)->get();
            @endphp
            @if($activeChallenges->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <span class="bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 p-1 rounded-full">🏆</span>
                    Défis de Lecture
                </h3>
                <div class="space-y-3">
                    @foreach($activeChallenges as $challenge)
                        <div class="p-3 rounded-xl bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 border border-orange-200 dark:border-orange-700">
                            <div class="flex items-center gap-2 mb-1">
                                @if($challenge->is_ai_generated)
                                    <span class="text-lg">🤖</span>
                                @else
                                    <span class="text-lg">📚</span>
                                @endif
                                <span class="font-medium text-sm text-gray-900 dark:text-white">{{ Str::limit($challenge->title, 25) }}</span>
                            </div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                                {{ $challenge->getTypeLabel() }} • {{ ucfirst($challenge->difficulty_level) }}
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 px-2 py-0.5 rounded-full">
                                    {{ $challenge->participants_count }} participant(s)
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $challenge->getDaysRemaining() }} jours restants
                                </span>
                            </div>
                            <div class="flex gap-1 mt-2">
                                <a href="{{ route('challenges.show', [$group, $challenge]) }}" class="flex-1 text-center text-orange-600 dark:text-orange-400 text-xs font-medium py-1.5 hover:bg-orange-100 dark:hover:bg-orange-900/20 rounded-lg transition-colors">
                                    Voir
                                </a>
                                @if(!$challenge->participants()->where('user_id', auth()->id())->exists())
                                    <form action="{{ route('challenges.join', [$group, $challenge]) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full text-center bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium py-1.5 rounded-lg transition-colors">
                                            Rejoindre
                                        </button>
                                    </form>
                                @else
                                    <span class="flex-1 text-center text-green-600 text-xs font-medium py-1.5 bg-green-100 dark:bg-green-900/20 rounded-lg">
                                        ✓ Inscrit
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="flex gap-2 mt-3">
                    <a href="{{ route('challenges.index', $group) }}" class="flex-1 text-center text-orange-600 dark:text-orange-400 text-sm font-medium py-2 hover:bg-orange-50 dark:hover:bg-orange-900/20 rounded-lg transition-colors">
                        Tous les défis
                    </a>
                    @if(auth()->user()->canCreateChallenges())
                        <form action="{{ route('challenges.generate', $group) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full text-center bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white text-sm font-medium py-2 rounded-lg transition-colors">
                                🤖 Générer IA
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endif

            <!-- Événements à venir -->
            @php
                $upcomingEvents = $group->upcomingEvents()->limit(3)->get();
            @endphp
            @if($upcomingEvents->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 p-1 rounded-full">📅</span>
                    Événements à venir
                </h3>
                <div class="space-y-3">
                    @foreach($upcomingEvents as $event)
                        <div class="p-3 rounded-xl bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 border border-indigo-200 dark:border-indigo-700">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-lg">{{ $event->type_details['icon'] }}</span>
                                <span class="font-medium text-sm text-gray-900 dark:text-white">{{ $event->title }}</span>
                            </div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">
                                {{ $event->start_datetime->format('d/m à H:i') }}
                                @if($event->location)
                                    • {{ Str::limit($event->location, 20) }}
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 px-2 py-0.5 rounded-full">
                                    {{ $event->approvedParticipants()->count() }} participant(s)
                                </span>
                                <a href="{{ route('groups.events.show', [$group->id, $event->id]) }}" class="text-indigo-600 dark:text-indigo-400 text-xs font-medium hover:underline">
                                    Voir détails
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('groups.events.index', $group->id) }}" class="w-full text-center text-indigo-600 dark:text-indigo-400 text-sm font-medium py-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors flex items-center justify-center gap-1 mt-3">
                    Voir tous les événements
                    <span>→</span>
                </a>
            </div>
            @endif

            <!-- Badges récents -->
            @if($recentBadges->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <span class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 p-1 rounded-full">🎉</span>
                    Badges récents
                </h3>
                <div class="space-y-2">
                    @foreach($recentBadges->take(3) as $badge)
                        <div class="flex items-center gap-2 text-sm">
                            <span class="text-lg">{{ $badge->badge_icon }}</span>
                            <div class="flex-1">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $badge->user->name }}</span>
                                <span class="text-gray-500 dark:text-gray-400">a obtenu</span>
                                <span class="font-medium" style="color: {{ $badge->badge_color }}">{{ $badge->badge_name }}</span>
                            </div>
                            <span class="text-xs text-gray-400">{{ $badge->earned_date->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Règles du groupe -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <span class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 p-1 rounded-full">📋</span>
                    Règles du groupe
                </h3>
                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                    <li class="flex items-start gap-2">
                        <span class="text-green-500 mt-0.5">•</span>
                        <span>Soyez respectueux des autres membres</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-500 mt-0.5">•</span>
                        <span>Partagez du contenu pertinent</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-500 mt-0.5">•</span>
                        <span>Pas de spam ou de publicité non autorisée</span>
                    </li>
                </ul>
            </div>
        </aside>
        
        <!-- Colonne centrale : Posts -->
        <main class="md:w-2/3 w-full flex flex-col gap-6 order-1 md:order-2">
            <!-- Formulaire de publication façon Facebook -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#f87171] to-[#fca5a5] rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                        <form action="{{ route('frontoffice.group.wall.post', $group->id) }}" method="POST" enctype="multipart/form-data" class="flex-1">
                        @csrf
                        <textarea name="content" rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 text-base resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white placeholder-gray-500 transition-all" placeholder="Exprimez-vous dans ce groupe..."></textarea>
                        <div class="flex justify-between items-center mt-3 px-1">
                            <div class="flex gap-2 items-center">
                                <label for="post-file" class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-[#f87171] dark:hover:text-[#fca5a5] transition-colors text-sm p-2 rounded-lg hover:bg-[#fee2e2] dark:hover:bg-[#fef2f2]/10 cursor-pointer">
                                    <span class="text-lg">🖼️</span>
                                    <span>Photo</span>
                                </label>
                                <label for="post-file" class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors text-sm p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                    <span class="text-lg">📹</span>
                                    <span>Vidéo</span>
                                </label>
                                <a href="{{ route('groups.events.create', $group->id) }}" class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors text-sm p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <span class="text-lg">📅</span>
                                    <span>Événement</span>
                                </a>
                                <label for="post-file" class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors text-sm p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                    <span class="text-lg">📄</span>
                                    <span>PDF</span>
                                </label>
                                
                                <!-- Boutons de génération automatique IA -->
                                <button type="button" onclick="generateAIPost('recommendation')" class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors text-sm p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <span class="text-lg">🤖</span>
                                    <span>Post IA</span>
                                </button>
                                <button type="button" onclick="generateAIPost('challenge')" class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-400 transition-colors text-sm p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <span class="text-lg">🏆</span>
                                    <span>Défi</span>
                                </button>
                                <input type="file" name="file" id="post-file" class="hidden" accept="image/*,video/*,application/pdf">
                            </div>
                            <button type="submit" class="bg-[#f87171] hover:bg-[#d42a03] text-white px-5 py-2 rounded-lg text-base font-medium shadow-md flex items-center gap-2 transition-all duration-150 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="text-lg">📤</span>
                                Publier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Filtres de publications -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-3">
                <div class="flex items-center gap-2 overflow-x-auto pb-1">
                    <button class="bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 px-3 py-1.5 rounded-full text-sm font-medium whitespace-nowrap">Tous les posts</button>
                    <button class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-full text-sm font-medium whitespace-nowrap hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Articles</button>
                    <button class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-full text-sm font-medium whitespace-nowrap hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Photos</button>
                    <button class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-full text-sm font-medium whitespace-nowrap hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Vidéos</button>
                    <button class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-full text-sm font-medium whitespace-nowrap hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Fichiers</button>
                </div>
            </div>
            
            <!-- Liste des publications -->
            @forelse($posts as $post)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-200 hover:shadow-lg" data-post-id="{{ $post->id }}">
                    <!-- En-tête du post -->
                    <div class="p-4 flex items-start gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#f87171] to-[#fca5a5] rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                            {{ substr($post->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $post->user->name }}</span>
                                
                                <!-- Badges de l'utilisateur du post -->
                                @php
                                    $userPostBadges = $post->user->getBadgesInGroup($group->id)->take(2);
                                @endphp
                                @foreach($userPostBadges as $badge)
                                    <span class="inline-flex items-center gap-1 bg-gradient-to-r from-yellow-100 to-orange-100 dark:from-yellow-900/30 dark:to-orange-900/30 text-xs px-2 py-0.5 rounded-full border border-yellow-200 dark:border-yellow-700"
                                          style="color: {{ $badge->badge_color }}"
                                          title="{{ $badge->badge_description }}">
                                        <span class="text-xs">{{ $badge->badge_icon }}</span>
                                        <span class="font-medium">{{ $badge->badge_name }}</span>
                                    </span>
                                @endforeach
                                
                                <span class="text-xs text-gray-500 dark:text-gray-400">•</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="text-gray-800 dark:text-gray-200 leading-relaxed mb-2 whitespace-pre-wrap">{{ $post->content }}</div>
                            @if($post->file)
                                <div class="mt-2">
                                    @php $ext = strtolower(pathinfo($post->file, PATHINFO_EXTENSION)); @endphp
                                    @if(in_array($ext, ['jpg','jpeg','png','gif']))
                                        <img src="{{ asset('storage/' . $post->file) }}" alt="Image" class="max-w-full rounded-lg shadow border max-h-80">
                                    @elseif(in_array($ext, ['mp4','mov','avi']))
                                        <video controls class="max-w-full rounded-lg shadow border max-h-80">
                                            <source src="{{ asset('storage/' . $post->file) }}" type="video/{{ $ext }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @elseif($ext === 'pdf')
                                        <a href="{{ asset('storage/' . $post->file) }}" target="_blank" class="inline-flex items-center space-x-1 text-blue-600 dark:text-blue-400 text-sm">
                                            <span>📄</span>
                                            <span>Document PDF</span>
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $post->file) }}" target="_blank" class="inline-flex items-center space-x-1 text-blue-600 dark:text-blue-400 text-sm">
                                            <span>📎</span>
                                            <span>Télécharger le fichier</span>
                                        </a>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Affichage des réactions existantes -->
                            <div class="reactions-display mb-2">
                                @if($post->reactions->count() > 0)
                                    <div class="flex items-center space-x-3">
                                        @php
                                            $reactionCounts = $post->reactions->groupBy('reaction_type')->map->count();
                                            $totalReactions = $post->reactions->count();
                                            $topReactions = $reactionCounts->sortDesc()->take(3);
                                        @endphp
                                        
                                        @foreach($topReactions as $type => $count)
                                            @php
                                                $reactionDetails = App\Models\PostReaction::REACTION_TYPES[$type] ?? App\Models\PostReaction::REACTION_TYPES['like'];
                                            @endphp
                                            <span class="inline-flex items-center space-x-1 text-sm">
                                                <span class="text-lg">{{ $reactionDetails['emoji'] }}</span>
                                                <span class="font-medium" style="color: {{ $reactionDetails['color'] }}">{{ $count }}</span>
                                            </span>
                                        @endforeach
                                        
                                        <span class="text-gray-500 text-sm">{{ $totalReactions }} réaction{{ $totalReactions > 1 ? 's' : '' }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-500 text-sm">Aucune réaction</span>
                                @endif
                            </div>

                            <!-- Actions du post avec système de réactions -->
                            <div class="flex justify-between items-center pt-2 border-t border-gray-100 dark:border-gray-700 mt-3">
                                <div class="flex items-center space-x-1">
                                    <!-- Bouton de réaction principal -->
                                    <button class="reaction-trigger flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors px-2 py-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 user-reaction-status"
                                            data-target-type="post" 
                                            data-target-id="{{ $post->id }}">
                                        @if($post->user_reaction)
                                            @php
                                                $userReactionDetails = App\Models\PostReaction::REACTION_TYPES[$post->user_reaction->reaction_type] ?? App\Models\PostReaction::REACTION_TYPES['like'];
                                            @endphp
                                            <span class="text-lg">{{ $userReactionDetails['emoji'] }}</span>
                                            <span class="text-sm font-semibold" style="color: {{ $userReactionDetails['color'] }}">{{ $userReactionDetails['label'] }}</span>
                                        @else
                                            <span class="text-lg">👍</span>
                                            <span class="text-sm font-medium">Réagir</span>
                                        @endif
                                    </button>
                                    
                                    <!-- Réactions rapides -->
                                    <div class="hidden md:flex items-center space-x-1 ml-2">
                                        @foreach(['like', 'love', 'laugh'] as $quickReaction)
                                            @php
                                                $quickDetails = App\Models\PostReaction::REACTION_TYPES[$quickReaction];
                                            @endphp
                                            <button class="reaction-button text-lg hover:scale-110 transition-transform p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700"
                                                    data-target-type="post"
                                                    data-target-id="{{ $post->id }}"
                                                    data-reaction-type="{{ $quickReaction }}"
                                                    title="{{ $quickDetails['label'] }}">
                                                {{ $quickDetails['emoji'] }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <button class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors px-2 py-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 comment-trigger" data-post-id="{{ $post->id }}">
                                    <span class="text-lg">💬</span>
                                    <span class="text-sm font-medium">Commenter ({{ $post->comments->count() }})</span>
                                </button>
                                <button class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors px-2 py-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <span class="text-lg">🔗</span>
                                    <span class="text-sm font-medium">Partager</span>
                                </button>
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors text-xl p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" title="Plus d'options">
                            ⋯
                        </button>
                    </div>
                    
                    <!-- Commentaires existants -->
                    @if($post->comments->count() > 0)
                        <div class="bg-gray-50 dark:bg-gray-750 px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                            <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2 flex items-center gap-1">
                                <span>💬</span>
                                {{ $post->comments->count() }} commentaire(s)
                            </div>
                            
                            @foreach($post->comments as $comment)
                                <div class="flex gap-3 mb-4 last:mb-0 items-start" data-comment-id="{{ $comment->id }}">
                                    <div class="w-8 h-8 bg-gradient-to-br from-[#f87171] to-[#fca5a5] rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm">
                                        {{ substr($comment->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="bg-white dark:bg-gray-700 rounded-xl px-3 py-2 mb-1 shadow-sm border border-[#fee2e2] dark:border-[#f87171]/20">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-semibold text-sm text-gray-900 dark:text-white">{{ $comment->user->name }}</span>
                                                
                                                <!-- Badge principal de l'utilisateur du commentaire -->
                                                @php
                                                    $userCommentBadge = $comment->user->getBadgesInGroup($group->id)->first();
                                                @endphp
                                                @if($userCommentBadge)
                                                    <span class="inline-flex items-center gap-1 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 text-xs px-1.5 py-0.5 rounded-full border border-yellow-200 dark:border-yellow-700"
                                                          style="color: {{ $userCommentBadge->badge_color }}"
                                                          title="{{ $userCommentBadge->badge_description }}">
                                                        <span class="text-xs">{{ $userCommentBadge->badge_icon }}</span>
                                                    </span>
                                                @endif
                                                
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-gray-800 dark:text-gray-200 mb-0">{{ $comment->content }}</p>
                                            @if($comment->file)
                                                <div class="mt-2">
                                                    @php $ext = strtolower(pathinfo($comment->file, PATHINFO_EXTENSION)); @endphp
                                                    @if(in_array($ext, ['jpg','jpeg','png','gif']))
                                                        <img src="{{ asset('storage/' . $comment->file) }}" alt="Image" class="max-w-full rounded-lg shadow border max-h-40">
                                                    @elseif($ext === 'pdf')
                                                        <a href="{{ asset('storage/' . $comment->file) }}" target="_blank" class="inline-flex items-center space-x-1 text-blue-600 dark:text-blue-400 text-sm">
                                                            <span>📄</span>
                                                            <span>Document PDF</span>
                                                        </a>
                                                    @else
                                                        <a href="{{ asset('storage/' . $comment->file) }}" target="_blank" class="inline-flex items-center space-x-1 text-blue-600 dark:text-blue-400 text-sm">
                                                            <span>📎</span>
                                                            <span>Télécharger le fichier</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <!-- Réactions du commentaire -->
                                        <div class="reactions-display mb-1">
                                            @if($comment->reactions->count() > 0)
                                                <div class="flex items-center space-x-2">
                                                    @php
                                                        $commentReactionCounts = $comment->reactions->groupBy('reaction_type')->map->count();
                                                        $commentTotalReactions = $comment->reactions->count();
                                                        $commentTopReactions = $commentReactionCounts->sortDesc()->take(2);
                                                    @endphp
                                                    
                                                    @foreach($commentTopReactions as $type => $count)
                                                        @php
                                                            $reactionDetails = App\Models\CommentReaction::REACTION_TYPES[$type] ?? App\Models\CommentReaction::REACTION_TYPES['like'];
                                                        @endphp
                                                        <span class="inline-flex items-center space-x-1 text-xs">
                                                            <span class="text-sm">{{ $reactionDetails['emoji'] }}</span>
                                                            <span class="font-medium" style="color: {{ $reactionDetails['color'] }}">{{ $count }}</span>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-xs">Aucune réaction</span>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-3 mt-1 px-1">
                                            <button class="reaction-trigger text-xs text-gray-500 dark:text-gray-400 hover:text-blue-500 transition-colors px-1 py-0.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700 user-reaction-status"
                                                    data-target-type="comment" 
                                                    data-target-id="{{ $comment->id }}">
                                                @if($comment->user_reaction)
                                                    @php
                                                        $userReactionDetails = App\Models\CommentReaction::REACTION_TYPES[$comment->user_reaction->reaction_type] ?? App\Models\CommentReaction::REACTION_TYPES['like'];
                                                    @endphp
                                                    <span class="text-sm">{{ $userReactionDetails['emoji'] }}</span>
                                                    <span style="color: {{ $userReactionDetails['color'] }}">{{ $userReactionDetails['label'] }}</span>
                                                @else
                                                    <span class="text-sm">👍</span>
                                                    <span>J'aime</span>
                                                @endif
                                            </button>
                                            
                                            <!-- Réactions rapides pour commentaires -->
                                            <div class="hidden sm:flex items-center space-x-1">
                                                @foreach(['love', 'laugh'] as $quickReaction)
                                                    @php
                                                        $quickDetails = App\Models\CommentReaction::REACTION_TYPES[$quickReaction];
                                                    @endphp
                                                    <button class="reaction-button text-sm hover:scale-110 transition-transform p-0.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700"
                                                            data-target-type="comment"
                                                            data-target-id="{{ $comment->id }}"
                                                            data-reaction-type="{{ $quickReaction }}"
                                                            title="{{ $quickDetails['label'] }}">
                                                        {{ $quickDetails['emoji'] }}
                                                    </button>
                                                @endforeach
                                            </div>
                                            
                                            <button class="text-xs text-gray-500 dark:text-gray-400 hover:text-blue-500 transition-colors px-1 py-0.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Répondre</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <!-- Formulaire de commentaire -->
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-750 border-t border-gray-100 dark:border-gray-700">
                        <form action="{{ route('frontoffice.group.comment', [$group->id, $post->id]) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                            @csrf
                            <div class="w-8 h-8 bg-gradient-to-br from-[#f87171] to-[#fca5a5] rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="flex-1 flex items-center bg-white dark:bg-gray-700 rounded-full px-3 py-1.5 border border-gray-200 dark:border-gray-600 shadow-sm">
                                <textarea name="content" rows="1" class="w-full border-0 bg-transparent focus:ring-0 text-sm resize-none dark:text-white placeholder-gray-500 focus:outline-none comment-field" data-post-id="{{ $post->id }}" placeholder="Écrire un commentaire..."></textarea>
                                <div class="flex items-center gap-1 ml-2">
                                    <button type="button" class="text-gray-400 hover:text-yellow-500 transition-colors p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600" title="Emoji">
                                        <span class="text-sm"></span>
                                    </button>
                                    <label for="file-{{ $post->id }}" class="cursor-pointer text-gray-400 hover:text-green-500 transition-colors p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600" title="Joindre un fichier">
                                        <span class="text-sm">📎</span>
                                    </label>
                                    <input type="file" name="file" id="file-{{ $post->id }}" class="hidden" accept="image/*,application/pdf">
                                    <button type="submit" name="send_file" class="ml-2 bg-[#f87171] hover:bg-[#ef4444] text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1 shadow transition-colors" style="min-width:90px;">
                                        <span class="text-base">📤</span> Envoyer fichier
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="bg-[#f87171] hover:bg-[#ef4444] text-white p-2 rounded-full text-sm font-medium shadow-sm transition-all duration-150 hover:shadow-md ml-1 comment-submit" data-post-id="{{ $post->id }}" disabled>
                                <span class="text-base">➡️</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-8 text-center">
                    <div class="text-6xl mb-4 text-gray-300 dark:text-gray-600">📝</div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Aucune publication</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Soyez le premier à publier dans ce groupe !</p>
                </div>
            @endforelse
        </main>
    </div>
</div>
@endsection

@push('styles')
<style>
.dark .bg-gray-750 {
    background-color: #2d3748;
}

/* Animation pour les nouveaux posts */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bg-white, .bg-gray-50 {
    animation: fadeInUp 0.5s ease-out;
}

/* Style pour la zone de texte qui s'agrandit */
textarea {
    transition: all 0.3s ease;
    min-height: 40px;
    max-height: 120px;
}

textarea:focus {
    border-radius: 0.75rem !important;
}

/* Style pour les avatars avec effet de profondeur */
.w-10.h-10, .w-8.h-8 {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Effets d'ombre modernes */
.shadow-md {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Transition fluide */
.transition-colors {
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
}

/* Scrollbar personnalisée */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.dark ::-webkit-scrollbar-track {
    background: #374151;
}

.dark ::-webkit-scrollbar-thumb {
    background: #6b7280;
}

.dark ::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Style pour les boutons d'action des posts */
button:hover {
    transform: translateY(-1px);
}

/* Style pour les champs de commentaire avec placeholder */
.comment-field::placeholder {
    color: #9ca3af;
}

.dark .comment-field::placeholder {
    color: #6b7280;
}

/* Style pour les icônes alphabet */
.w-6.h-6, .w-5.h-5 {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

/* Styles pour le système de réactions */
.reaction-picker {
    animation: fadeInScale 0.2s ease-out;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.8) translateY(10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.reaction-option:hover {
    animation: bounceScale 0.3s ease;
}

@keyframes bounceScale {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}

.reaction-button:hover {
    animation: pulseReaction 0.3s ease;
}

@keyframes pulseReaction {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* Effet de hover pour les boutons de réaction */
.user-reaction-status:hover {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1));
}

/* Réactions actives */
.user-reaction-status.active {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(147, 51, 234, 0.2));
    font-weight: 600;
}

/* Animation pour les nouveaux compteurs de réactions */
.reactions-display {
    animation: slideInLeft 0.3s ease-out;
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/group-reactions.js') }}"></script>
<script>
// Système de notification des badges
class BadgeNotificationSystem {
    constructor() {
        this.checkForNewBadges();
    }

    async checkForNewBadges() {
        try {
            const response = await fetch(`/groups/{{ $group->id }}/badges/evaluate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.awarded_badges && data.awarded_badges.length > 0) {
                    this.showBadgeNotifications(data.awarded_badges);
                }
            }
        } catch (error) {
            console.log('Badge evaluation failed:', error);
        }
    }

    showBadgeNotifications(badges) {
        badges.forEach((badge, index) => {
            setTimeout(() => {
                this.createBadgeNotification(badge);
            }, index * 1000);
        });
    }

    createBadgeNotification(badge) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white p-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="text-3xl">${badge.badge_icon}</div>
                <div>
                    <div class="font-bold">Nouveau badge obtenu !</div>
                    <div class="text-sm opacity-90">${badge.badge_name}</div>
                    <div class="text-xs opacity-75">+${badge.points_earned} points</div>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200 text-xl font-bold">×</button>
            </div>
        `;

        document.body.appendChild(notification);

        // Animation d'entrée
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Auto-suppression après 5 secondes
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le système de badges
    new BadgeNotificationSystem();
    // Gestion du textarea de publication principale
    const mainTextarea = document.querySelector('textarea[name="content"]');
    const mainSubmitBtn = document.querySelector('form[action*="wall.post"] button[type="submit"]');
    
    if (mainTextarea && mainSubmitBtn) {
        mainSubmitBtn.disabled = mainTextarea.value.trim().length === 0;
        
        mainTextarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
            mainSubmitBtn.disabled = this.value.trim().length === 0;
        });
    }
    
    // Gestion des champs de commentaire
    const commentFields = document.querySelectorAll('.comment-field');
    const commentSubmits = document.querySelectorAll('.comment-submit');
    const commentTriggers = document.querySelectorAll('.comment-trigger');
    
    commentFields.forEach(field => {
        const postId = field.dataset.postId;
        const submitBtn = document.querySelector(`.comment-submit[data-post-id="${postId}"]`);
        
        if (submitBtn) {
            submitBtn.disabled = field.value.trim().length === 0;
            
            field.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
                submitBtn.disabled = this.value.trim().length === 0;
            });
        }
    });
    
    // Focus sur le champ de commentaire quand on clique sur "Commenter"
    commentTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const commentField = document.querySelector(`.comment-field[data-post-id="${postId}"]`);
            if (commentField) {
                commentField.focus();
            }
        });
    });
    
    // Animation pour les nouveaux posts
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) {
                        node.style.animation = 'fadeInUp 0.5s ease-out';
                    }
                });
            }
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Fonctions de génération automatique de posts IA
    window.generateAIPost = function(type) {
        const modal = document.getElementById('aiPostModal');
        const modalTitle = document.getElementById('aiModalTitle');
        const modalContent = document.getElementById('aiModalContent');
        
        if (type === 'recommendation') {
            modalTitle.textContent = '🤖 Générer une Recommandation IA';
            modalContent.innerHTML = `
                <p class="text-gray-600 dark:text-gray-400 mb-4">L'IA va analyser les goûts du groupe et générer une recommandation de livre personnalisée.</p>
                <div class="space-y-3">
                    <label class="block">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Type de recommandation</span>
                        <select name="recommendation_type" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            <option value="genre">Basée sur le genre préféré</option>
                            <option value="author">Découverte d'auteur</option>
                            <option value="trending">Livre tendance</option>
                            <option value="classic">Classique incontournable</option>
                        </select>
                    </label>
                    <button onclick="executeAIGeneration('recommendation')" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors">
                        🚀 Générer la Recommandation
                    </button>
                </div>
            `;
        } else if (type === 'challenge') {
            modalTitle.textContent = '🏆 Générer un Post de Défi';
            modalContent.innerHTML = `
                <p class="text-gray-600 dark:text-gray-400 mb-4">Créer un post pour motiver le groupe avec un mini-défi de lecture.</p>
                <div class="space-y-3">
                    <label class="block">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Type de défi</span>
                        <select name="challenge_type" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            <option value="daily">Défi quotidien</option>
                            <option value="weekly">Défi hebdomadaire</option>
                            <option value="theme">Défi thématique</option>
                            <option value="speed">Défi de rapidité</option>
                        </select>
                    </label>
                    <button onclick="executeAIGeneration('challenge')" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                        🎯 Créer le Défi
                    </button>
                </div>
            `;
        }
        
        modal.classList.remove('hidden');
    };

    window.executeAIGeneration = function(type) {
        const modal = document.getElementById('aiPostModal');
        const loadingDiv = document.getElementById('aiLoadingDiv');
        const modalContent = document.getElementById('aiModalContent');
        
        // Afficher le loading
        modalContent.classList.add('hidden');
        loadingDiv.classList.remove('hidden');
        
        // Simuler l'appel API (remplacer par un vrai appel)
        setTimeout(() => {
            const textarea = document.querySelector('textarea[name="content"]');
            let generatedContent = '';
            
            if (type === 'recommendation') {
                const recommendations = [
                    "📚 **Recommandation IA du Jour** ✨\n\nAprès analyse de nos discussions récentes, je vous recommande \"Le Problème à trois corps\" de Liu Cixin ! 🚀\n\n🔍 **Pourquoi ce livre ?**\n• Science-fiction hard captivante\n• Prix Hugo 2015\n• Premier tome d'une trilogie addictive\n\nQui se lance dans cette aventure intergalactique ? 🌌",
                    "🤖 **L'IA a une suggestion pour vous !**\n\nBasé sur vos échanges sur les thrillers psychologiques, que diriez-vous de \"Gone Girl\" de Gillian Flynn ? 😱\n\n✨ **Points forts :**\n• Suspense haletant\n• Personnages complexes\n• Retournements de situation\n\nParfait pour vos soirées d'automne ! 🍂 Qui est tenté ?",
                    "🎯 **Recommandation Personnalisée IA**\n\nVotre groupe semble apprécier les romans historiques... Et si on découvrait \"Les Piliers de la Terre\" de Ken Follett ? 🏰\n\n📖 **Pourquoi maintenant ?**\n• Roman épique médiéval\n• 1000 pages d'immersion totale\n• Parfait pour l'automne\n\nQui est prêt pour cette épopée ? ⚔️"
                ];
                generatedContent = recommendations[Math.floor(Math.random() * recommendations.length)];
            } else if (type === 'challenge') {
                const challenges = [
                    "🏆 **Mini-Défi de la Semaine !**\n\nQui peut lire 50 pages avant dimanche ? 📖\n\n🎯 **Règles simples :**\n• N'importe quel livre\n• Partagez votre progression\n• Encouragez les autres !\n\n🏅 **Récompense :** Badge \"Lecteur Express\" pour tous les participants !\n\nQui relève le défi ? Réagissez avec 🔥 pour participer !",
                    "⚡ **Défi Flash : Genre Mystère !**\n\nAujourd'hui, lisez 3 premières pages de 3 livres différents et devinez leur genre ! 🕵️\n\n🎮 **Challenge :**\n• Choisissez 3 livres au hasard\n• Lisez les premières pages\n• Devinez : Romance ? Thriller ? SF ?\n\n📝 Partagez vos prédictions en commentaire ! Le plus créatif gagne ! 🏆",
                    "🌟 **Défi Thématique : Couleurs !**\n\nCette semaine, ne lisez que des livres avec une COULEUR dans le titre ! 🎨\n\n🌈 **Exemples :**\n• Le Petit Prince (Bleu de ses yeux)\n• Rouge Brésil\n• La Dame en Blanc\n\nQui trouve le titre le plus original ? Postez vos découvertes ! 📚✨"
                ];
                generatedContent = challenges[Math.floor(Math.random() * challenges.length)];
            }
            
            textarea.value = generatedContent;
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
            
            // Cacher le modal
            modal.classList.add('hidden');
            loadingDiv.classList.add('hidden');
            modalContent.classList.remove('hidden');
            
            // Afficher message de succès
            const successToast = document.createElement('div');
            successToast.className = 'fixed z-50 right-6 top-6 bg-green-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 animate-fade-in-up';
            successToast.innerHTML = '<span class="text-xl">✅</span><span>Post généré avec succès ! Vous pouvez maintenant le modifier.</span>';
            document.body.appendChild(successToast);
            
            setTimeout(() => successToast.remove(), 3000);
        }, 2000);
    };

    window.closeAIModal = function() {
        document.getElementById('aiPostModal').classList.add('hidden');
    };
});
</script>

<!-- Modal pour la génération IA -->
<div id="aiPostModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 id="aiModalTitle" class="text-lg font-bold text-gray-900 dark:text-white"></h3>
                <button onclick="closeAIModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            
            <div id="aiModalContent">
                <!-- Contenu dynamique -->
            </div>
            
            <!-- Loading state -->
            <div id="aiLoadingDiv" class="hidden text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <p class="text-gray-600 dark:text-gray-400">🤖 L'IA génère votre contenu...</p>
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Analyse en cours des préférences du groupe</p>
            </div>
        </div>
    </div>
</div>
@endpush