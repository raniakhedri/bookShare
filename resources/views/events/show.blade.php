@extends('frontoffice.layouts.app')

@section('title', $event->title . ' - ' . $group->name)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('groups.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary dark:text-gray-400 dark:hover:text-white transition-colors duration-200">
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
                    <a href="{{ route('groups.show', $group) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary md:ml-2 dark:text-gray-400 dark:hover:text-white transition-colors duration-200">{{ $group->name }}</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400 truncate max-w-xs">{{ $event->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <!-- En-tête de l'événement -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <!-- Cover Image ou Gradient -->
                <div class="h-32 bg-gradient-to-r from-{{ App\Models\GroupEvent::EVENT_TYPES[$event->type]['color'] }}-400 to-{{ App\Models\GroupEvent::EVENT_TYPES[$event->type]['color'] }}-600 relative">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                    <div class="absolute bottom-4 right-4">
                        @if($event->status === 'draft')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Brouillon
                            </span>
                        @elseif($event->status === 'published')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Publié
                            </span>
                        @elseif($event->status === 'cancelled')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Annulé
                            </span>
                        @elseif($event->status === 'completed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                                </svg>
                                Terminé
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="text-4xl">{{ App\Models\GroupEvent::EVENT_TYPES[$event->type]['icon'] }}</span>
                                <div>
                                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">{{ $event->title }}</h1>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ App\Models\GroupEvent::EVENT_TYPES[$event->type]['color'] }}-100 text-{{ App\Models\GroupEvent::EVENT_TYPES[$event->type]['color'] }}-800 dark:bg-{{ App\Models\GroupEvent::EVENT_TYPES[$event->type]['color'] }}-900 dark:text-{{ App\Models\GroupEvent::EVENT_TYPES[$event->type]['color'] }}-300">
                                            {{ App\Models\GroupEvent::EVENT_TYPES[$event->type]['label'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400">
                                Organisé par <span class="font-semibold text-gray-900 dark:text-white">{{ $event->creator->name }}</span>
                            </p>
                        </div>
                    </div>
                    <!-- Informations principales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-4 8l4-4m0 4l-4-4m-6 4a2 2 0 01-2-2V7a2 2 0 012-2h4"></path>
                                    </svg>
                                    Date et heure
                                </h3>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="flex items-center text-gray-900 dark:text-white mb-2">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="font-medium">Début :</span> {{ $event->start_datetime->format('d/m/Y à H:i') }}
                                    </div>
                                    @if($event->end_datetime)
                                        <div class="flex items-center text-gray-900 dark:text-white">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="font-medium">Fin :</span> {{ $event->end_datetime->format('d/m/Y à H:i') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                @if($event->is_virtual)
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        Événement virtuel
                                    </h3>
                                    @if($event->meeting_link)
                                        <a href="{{ $event->meeting_link }}" target="_blank" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            Rejoindre la réunion
                                        </a>
                                    @endif
                                @else
                                    @if($event->location)
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Lieu
                                        </h3>
                                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                            <p class="text-gray-900 dark:text-white">{{ $event->location }}</p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zm-13.5 0a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    Participants
                                </h3>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-2xl font-bold text-green-600 dark:text-green-400">
                                            {{ $event->participants->where('status', 'approved')->count() + $event->participants->where('status', 'attended')->count() }}
                                        </span>
                                        <span class="text-gray-600 dark:text-gray-400 text-sm">
                                            participant(s) confirmé(s)
                                            @if($event->max_participants)
                                                <br>sur {{ $event->max_participants }} maximum
                                            @endif
                                        </span>
                                    </div>
                                    
                                    @if($event->requires_approval)
                                        <div class="flex items-center text-amber-600 dark:text-amber-400 text-sm mt-2">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Approbation requise
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Description</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <p class="text-gray-900 dark:text-white leading-relaxed">{{ $event->description }}</p>
                        </div>
                    </div>

                    <!-- Ressources et prérequis -->
                    @if($event->resources || $event->requirements)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            @if($event->resources)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        Ressources fournies
                                    </h3>
                                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                                        <ul class="space-y-2">
                                            @foreach($event->resources as $resource)
                                                <li class="flex items-center text-green-800 dark:text-green-300">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    {{ $resource }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            
                            @if($event->requirements)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        Prérequis
                                    </h3>
                                    <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-4">
                                        <p class="text-amber-800 dark:text-amber-300">{{ $event->requirements }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Actions utilisateur -->
                    <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                        @auth
                            @if($event->status === 'published' && $event->start_datetime->isFuture())
                                @php
                                    $userParticipation = $event->participants->where('user_id', auth()->id())->first();
                                @endphp
                                
                                @if(!$userParticipation)
                                    @if(!$event->max_participants || $event->participants->where('status', 'approved')->count() < $event->max_participants)
                                        <button type="button" onclick="document.getElementById('registerModal').classList.remove('hidden')" 
                                                class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary-dark text-white font-medium rounded-lg transition-colors duration-200 transform hover:scale-105">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                            </svg>
                                            S'inscrire à l'événement
                                        </button>
                                    @else
                                        <button disabled 
                                                class="inline-flex items-center px-6 py-3 bg-gray-400 text-white font-medium rounded-lg cursor-not-allowed">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zm-13.5 0a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                            </svg>
                                            Événement complet
                                        </button>
                                    @endif
                                @else
                                    @if($userParticipation->status === 'pending')
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                En attente d'approbation
                                            </span>
                                            <button type="button" onclick="unregisterFromEvent()" 
                                                    class="inline-flex items-center px-4 py-2 border border-red-300 text-red-700 bg-white hover:bg-red-50 font-medium rounded-lg transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Annuler inscription
                                            </button>
                                        </div>
                                    @elseif($userParticipation->status === 'approved')
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Inscription confirmée
                                            </span>
                                            <button type="button" onclick="unregisterFromEvent()" 
                                                    class="inline-flex items-center px-4 py-2 border border-red-300 text-red-700 bg-white hover:bg-red-50 font-medium rounded-lg transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Se désinscrire
                                            </button>
                                        </div>
                                    @elseif($userParticipation->status === 'rejected')
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Inscription refusée
                                        </span>
                                    @endif
                                @endif
                            @endif
                            
                            @if(auth()->id() === $event->creator_id || auth()->user()->can('moderate', $group))
                                <a href="{{ route('groups.events.edit', [$group, $event]) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Modifier
                                </a>
                                @if($event->status === 'published')
                                    <button type="button" onclick="toggleEventStatus('cancelled')" 
                                            class="inline-flex items-center px-4 py-2 border border-orange-300 text-orange-700 bg-white hover:bg-orange-50 font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                        </svg>
                                        Annuler l'événement
                                    </button>
                                @endif
                            @endif
                        @endauth
                        
                        <button type="button" onclick="shareEvent()" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                            Partager
                        </button>
                    </div>
                </div>
            </div>

            <!-- Liste des participants -->
            @if($event->participants->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zm-13.5 0a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Participants</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $event->participants->count() }} personnes inscrites</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @foreach($event->participants->groupBy('status') as $status => $participants)
                                <div class="space-y-3">
                                    <div class="flex items-center mb-4">
                                        @if($status === 'approved')
                                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-md font-medium text-gray-900 dark:text-white">
                                                Confirmés <span class="text-green-600 dark:text-green-400">({{ $participants->count() }})</span>
                                            </h4>
                                        @elseif($status === 'pending')
                                            <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-md font-medium text-gray-900 dark:text-white">
                                                En attente <span class="text-yellow-600 dark:text-yellow-400">({{ $participants->count() }})</span>
                                            </h4>
                                        @elseif($status === 'attended')
                                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-md font-medium text-gray-900 dark:text-white">
                                                Présents <span class="text-blue-600 dark:text-blue-400">({{ $participants->count() }})</span>
                                            </h4>
                                        @elseif($status === 'absent')
                                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-md font-medium text-gray-900 dark:text-white">
                                                Absents <span class="text-gray-600 dark:text-gray-400">({{ $participants->count() }})</span>
                                            </h4>
                                        @else
                                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-md font-medium text-gray-900 dark:text-white">
                                                {{ ucfirst($status) }} <span class="text-gray-600 dark:text-gray-400">({{ $participants->count() }})</span>
                                            </h4>
                                        @endif
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($participants as $participant)
                                            <div class="flex items-center">
                                                @if($status === 'approved')
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 border border-green-200 dark:border-green-800">
                                                        <img src="{{ $participant->user->avatar_url ?? 'https://ui-avatars.io/api/?name=' . urlencode($participant->user->name) }}" 
                                                             alt="{{ $participant->user->name }}" 
                                                             class="w-4 h-4 rounded-full mr-2 object-cover">
                                                        {{ $participant->user->name }}
                                                    </span>
                                                @elseif($status === 'pending')
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800">
                                                        <img src="{{ $participant->user->avatar_url ?? 'https://ui-avatars.io/api/?name=' . urlencode($participant->user->name) }}" 
                                                             alt="{{ $participant->user->name }}" 
                                                             class="w-4 h-4 rounded-full mr-2 object-cover">
                                                        {{ $participant->user->name }}
                                                    </span>
                                                @elseif($status === 'attended')
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                                        <img src="{{ $participant->user->avatar_url ?? 'https://ui-avatars.io/api/?name=' . urlencode($participant->user->name) }}" 
                                                             alt="{{ $participant->user->name }}" 
                                                             class="w-4 h-4 rounded-full mr-2 object-cover">
                                                        {{ $participant->user->name }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                                        <img src="{{ $participant->user->avatar_url ?? 'https://ui-avatars.io/api/?name=' . urlencode($participant->user->name) }}" 
                                                             alt="{{ $participant->user->name }}" 
                                                             class="w-4 h-4 rounded-full mr-2 object-cover">
                                                        {{ $participant->user->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <!-- Informations du groupe -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Groupe organisateur</h3>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <img src="{{ $group->image ? asset('storage/' . $group->image) : asset('images/default-group.png') }}" 
                                 alt="{{ $group->name }}" 
                                 class="w-12 h-12 rounded-lg object-cover border-2 border-gray-200 dark:border-gray-600">
                        </div>
                        <div class="ml-4 flex-grow">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-white">{{ $group->name }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $group->members->count() }} membre{{ $group->members->count() > 1 ? 's' : '' }}</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('groups.show', $group) }}" 
                       class="inline-flex items-center w-full justify-center px-4 py-2 border border-primary text-primary bg-white hover:bg-primary hover:text-white dark:bg-gray-800 dark:border-primary dark:text-primary dark:hover:bg-primary dark:hover:text-white font-medium rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Voir le groupe
                    </a>
                </div>
            </div>

            <!-- Autres événements du groupe -->
            @php
                $otherEvents = $group->events()
                    ->where('id', '!=', $event->id)
                    ->where('status', 'published')
                    ->where('start_datetime', '>', now())
                    ->orderBy('start_datetime')
                    ->limit(3)
                    ->get();
            @endphp
            
            @if($otherEvents->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Autres événements à venir</h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($otherEvents as $otherEvent)
                                <div class="border-b border-gray-200 dark:border-gray-600 pb-4 last:border-b-0 last:pb-0">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            @php
                                                $eventType = App\Models\GroupEvent::EVENT_TYPES[$otherEvent->type] ?? ['color' => 'gray', 'icon' => '📅'];
                                                $colorClasses = [
                                                    'primary' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                                    'success' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                    'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                                    'danger' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                                    'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                                    'secondary' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                ];
                                                $colorClass = $colorClasses[$eventType['color']] ?? $colorClasses['secondary'];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                                {{ $eventType['icon'] }}
                                            </span>
                                        </div>
                                        <div class="flex-grow min-w-0">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                                                <a href="{{ route('groups.events.show', [$group, $otherEvent]) }}" 
                                                   class="hover:text-primary transition-colors duration-200">
                                                    {{ $otherEvent->title }}
                                                </a>
                                            </h4>
                                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $otherEvent->start_datetime->format('d/m/Y à H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('groups.events.index', $group) }}" 
                               class="text-sm text-primary hover:text-primary-dark font-medium">
                                Voir tous les événements du groupe →
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal d'inscription -->
@auth
    @php
        $userParticipation = $event->participants->where('user_id', auth()->id())->first();
    @endphp
    
    @if(!$userParticipation && $event->status === 'published' && $event->start_datetime->isFuture())
        <div id="registerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 xl:w-2/5 shadow-lg rounded-xl bg-white dark:bg-gray-800 dark:border-gray-700">
                <form action="{{ route('groups.events.register', [$group, $event]) }}" method="POST">
                    @csrf
                    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">S'inscrire à l'événement</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $event->title }}</p>
                            </div>
                        </div>
                        <button type="button" onclick="document.getElementById('registerModal').classList.add('hidden')" 
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="registration_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Message d'inscription
                                <span class="text-gray-400 font-normal">(optionnel)</span>
                            </label>
                            <textarea class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 resize-none transition-all duration-200" 
                                      id="registration_message" 
                                      name="registration_message" 
                                      rows="3" 
                                      placeholder="Partagez votre motivation ou posez vos questions..."></textarea>
                        </div>
                        
                        <div>
                            <label for="additional_info" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Informations supplémentaires
                                <span class="text-gray-400 font-normal">(optionnel)</span>
                            </label>
                            <textarea class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 resize-none transition-all duration-200" 
                                      id="additional_info" 
                                      name="additional_info" 
                                      rows="2" 
                                      placeholder="Régime alimentaire, contraintes particulières..."></textarea>
                        </div>
                        
                        @if($event->requires_approval)
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300">Approbation requise</h4>
                                        <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">
                                            Votre inscription nécessitera une approbation de l'organisateur avant d'être confirmée.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-600">
                        <button type="button" onclick="document.getElementById('registerModal').classList.add('hidden')" 
                                class="px-6 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition-colors duration-200">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2.5 bg-primary hover:bg-primary-dark text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            S'inscrire à l'événement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endauth

<script>
function unregisterFromEvent() {
    if (confirm('Êtes-vous sûr de vouloir annuler votre inscription ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("groups.events.unregister", [$group, $event]) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function toggleEventStatus(status) {
    const message = status === 'cancelled' ? 
        'Êtes-vous sûr de vouloir annuler cet événement ?' : 
        'Êtes-vous sûr de vouloir modifier le statut de cet événement ?';
        
    if (confirm(message)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("groups.events.update", [$group, $event]) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';
        form.appendChild(methodField);
        
        const statusField = document.createElement('input');
        statusField.type = 'hidden';
        statusField.name = 'status';
        statusField.value = status;
        form.appendChild(statusField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function shareEvent() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $event->title }}',
            text: '{{ $event->description }}',
            url: window.location.href
        });
    } else {
        // Fallback - copier l'URL
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Lien copié dans le presse-papier !');
        });
    }
}
</script>
@endsection