@extends('frontoffice.layouts.app')

@section('title', 'Événements - ' . $group->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- En-tête avec breadcrumb et actions -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('groups.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Groupes
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('groups.show', $group) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary md:ml-2 dark:text-gray-400 dark:hover:text-white">{{ $group->name }}</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Événements</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8">
        <div class="mb-4 lg:mb-0">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                <span class="text-4xl mr-3">📅</span>
                Événements du groupe
            </h1>
            <p class="text-gray-600 dark:text-gray-300">{{ $group->name }}</p>
        </div>
        
        @auth
            @if($group->members->contains(auth()->user()) || auth()->user()->can('moderate', $group))
                <a href="{{ route('groups.events.create', $group) }}" class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary-dark text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Créer un événement
                </a>
            @endif
        @endauth
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <!-- Événements à venir -->
            @if($upcomingEvents->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg mb-8">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                            <span class="text-2xl mr-3">⏰</span>
                            Événements à venir ({{ $upcomingEvents->count() }})
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($upcomingEvents as $event)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <div class="flex flex-col md:flex-row md:items-center justify-between">
                                    <div class="flex items-start space-x-4 mb-4 md:mb-0 flex-1">
                                        <!-- Date -->
                                        <div class="flex-shrink-0 text-center bg-gray-100 dark:bg-gray-600 rounded-lg p-3">
                                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $event->start_datetime->format('d') }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-300 uppercase">{{ $event->start_datetime->format('M') }}</div>
                                        </div>
                                        
                                        <!-- Contenu -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="text-2xl">{{ $eventTypes[$event->type]['icon'] }}</span>
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    <a href="{{ route('groups.events.show', [$group, $event]) }}" 
                                                       class="hover:text-primary transition-colors duration-200">
                                                        {{ $event->title }}
                                                    </a>
                                                </h4>
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $eventTypes[$event->type]['color'] }}-100 text-{{ $eventTypes[$event->type]['color'] }}-800 dark:bg-{{ $eventTypes[$event->type]['color'] }}-900 dark:text-{{ $eventTypes[$event->type]['color'] }}-300">
                                                    {{ $eventTypes[$event->type]['label'] }}
                                                </span>
                                            </div>
                                            
                                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-3 line-clamp-2">
                                                {{ Str::limit($event->description, 120) }}
                                            </p>
                                            
                                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $event->start_datetime->format('H:i') }}
                                                </div>
                                                
                                                @if($event->is_virtual)
                                                    <div class="flex items-center text-blue-600 dark:text-blue-400">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                        </svg>
                                                        Virtuel
                                                    </div>
                                                @elseif($event->location)
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        {{ Str::limit($event->location, 30) }}
                                                    </div>
                                                @endif
                                                
                                                <div class="flex items-center text-green-600 dark:text-green-400">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zm-13.5 0a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                                    </svg>
                                                    {{ $event->approvedParticipants->count() }} participant(s)
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="flex flex-col gap-2 md:ml-4">
                                        @auth
                                            @if(isset($event->user_registration_status))
                                                @if($event->user_registration_status === 'not_registered')
                                                    @if($event->canUserRegister(auth()->id()))
                                                        <a href="{{ route('groups.events.show', [$group, $event]) }}" 
                                                           class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                                            </svg>
                                                            S'inscrire
                                                        </a>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                            Complet
                                                        </span>
                                                    @endif
                                                @elseif($event->user_registration_status === 'pending')
                                                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                        En attente
                                                    </span>
                                                @elseif($event->user_registration_status === 'approved')
                                                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                        Inscrit
                                                    </span>
                                                @elseif($event->user_registration_status === 'rejected')
                                                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                        Refusé
                                                    </span>
                                                @endif
                                            @endif
                                        @endauth
                                        
                                        <a href="{{ route('groups.events.show', [$group, $event]) }}" 
                                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Événements passés -->
            @if($pastEvents->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                            <span class="text-2xl mr-3">📚</span>
                            Événements passés ({{ $pastEvents->count() }})
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($pastEvents as $event)
                            <div class="p-6 opacity-75 hover:opacity-100 transition-opacity duration-200">
                                <div class="flex flex-col md:flex-row md:items-center justify-between">
                                    <div class="flex items-start space-x-4 mb-4 md:mb-0 flex-1">
                                        <!-- Date -->
                                        <div class="flex-shrink-0 text-center bg-gray-100 dark:bg-gray-600 rounded-lg p-3">
                                            <div class="text-xl font-bold text-gray-600 dark:text-gray-400">{{ $event->start_datetime->format('d') }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-500 uppercase">{{ $event->start_datetime->format('M') }}</div>
                                        </div>
                                        
                                        <!-- Contenu -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="text-xl opacity-60">{{ $eventTypes[$event->type]['icon'] }}</span>
                                                <h4 class="text-lg font-medium text-gray-600 dark:text-gray-400">{{ $event->title }}</h4>
                                                
                                                @if($event->status === 'completed')
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                        Terminé
                                                    </span>
                                                @elseif($event->status === 'cancelled')
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                        Annulé
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <p class="text-gray-500 dark:text-gray-500 text-sm mb-3 line-clamp-2">
                                                {{ Str::limit($event->description, 100) }}
                                            </p>
                                            
                                            <div class="flex items-center gap-4 text-sm text-gray-400">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $event->start_datetime->format('H:i') }}
                                                </div>
                                                
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zm-13.5 0a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                                    </svg>
                                                    {{ $event->approvedParticipants->count() }} participant(s)
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="md:ml-4">
                                        <a href="{{ route('groups.events.show', [$group, $event]) }}" 
                                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Voir
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Aucun événement -->
            @if($upcomingEvents->count() === 0 && $pastEvents->count() === 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">📅</div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Aucun événement</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Ce groupe n'a pas encore d'événements organisés.</p>
                        
                        @auth
                            @if($group->members->contains(auth()->user()))
                                <a href="{{ route('groups.events.create', $group) }}" class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary-dark text-white font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Créer le premier événement
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <!-- Informations du groupe -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="text-xl mr-3">👥</span>
                    Informations du groupe
                </h3>
                
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex-shrink-0">
                        <img src="{{ $group->image ? asset('storage/' . $group->image) : asset('template/images/default-group.png') }}" 
                             alt="{{ $group->name }}" class="w-16 h-16 rounded-lg object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white truncate">{{ $group->name }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $group->members->count() }} membre(s)</p>
                    </div>
                </div>
                
                @if($group->description)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($group->description, 120) }}</p>
                @endif
                
                <a href="{{ route('groups.show', $group) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour au groupe
                </a>
            </div>

            <!-- Statistiques -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="text-xl mr-3">📊</span>
                    Statistiques
                </h3>
                
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $upcomingEvents->count() }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">À venir</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $pastEvents->count() }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Passés</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $upcomingEvents->sum(fn($e) => $e->approvedParticipants->count()) }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Inscrits</div>
                    </div>
                </div>
            </div>

            <!-- Types d'événements -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="text-xl mr-3">🏷️</span>
                    Types d'événements
                </h3>
                
                <div class="grid grid-cols-2 gap-3">
                    @foreach($eventTypes as $key => $type)
                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-700 text-center">
                            <div class="text-2xl mb-2">{{ $type['icon'] }}</div>
                            <div class="text-xs font-medium text-gray-900 dark:text-white mb-1">
                                {{ $type['label'] }}
                            </div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">
                                {{ $type['description'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush
@endsection