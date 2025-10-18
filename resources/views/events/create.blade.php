@extends('frontoffice.layouts.app')

@section('title', 'Créer un événement - ' . $group->name)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Messages de validation -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">Veuillez corriger les erreurs suivantes :</span>
            </div>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
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
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Créer un événement</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- En-tête -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-primary to-primary-dark rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Créer un nouvel événement</h1>
                        <p class="text-gray-600 dark:text-gray-400">Pour le groupe <span class="font-medium text-primary">{{ $group->name }}</span></p>
                    </div>
                </div>
                <a href="{{ route('groups.show', $group) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour au groupe
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-8">
                    <form action="{{ route('groups.events.store', $group) }}" method="POST" id="eventForm" class="space-y-8">
                        @csrf
                        
                        <!-- Section 1: Informations de base -->
                        <div class="space-y-6">
                            <div class="flex items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informations de base</h2>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="md:col-span-2">
                                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Titre de l'événement <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white placeholder-gray-400 transition-all duration-200 @error('title') border-red-500 @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title') }}" 
                                           placeholder="Ex: Atelier de lecture, Rencontre littéraire..."
                                           required>
                                    @error('title')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Type d'événement <span class="text-red-500">*</span>
                                    </label>
                                    <select class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 @error('type') border-red-500 @enderror" 
                                            id="type" 
                                            name="type" 
                                            required>
                                        <option value="">Choisir un type</option>
                                        @foreach(App\Models\GroupEvent::EVENT_TYPES as $key => $typeInfo)
                                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                                {{ $typeInfo['icon'] }} {{ $typeInfo['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Description -->
                        <div class="space-y-6">
                            <div class="flex items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Description détaillée</h2>
                            </div>
                            
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Description de l'événement <span class="text-red-500">*</span>
                                </label>
                                <textarea class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white placeholder-gray-400 resize-none transition-all duration-200 @error('description') border-red-500 @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="5" 
                                          placeholder="Décrivez votre événement de manière détaillée. Que vont faire les participants ? Quels sont les objectifs ? Qu'apporteront-ils ?"
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Une description détaillée attire plus de participants et leur permet de mieux se préparer.
                                </p>
                            </div>
                        </div>

                        <!-- Section 3: Planification -->
                        <div class="space-y-6">
                            <div class="flex items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Planification temporelle</h2>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="start_datetime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Date et heure de début <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 @error('start_datetime') border-red-500 @enderror" 
                                           id="start_datetime" 
                                           name="start_datetime" 
                                           value="{{ old('start_datetime') }}" 
                                           required>
                                    @error('start_datetime')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="end_datetime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Date et heure de fin <span class="text-gray-400">(optionnel)</span>
                                    </label>
                                    <input type="datetime-local" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 @error('end_datetime') border-red-500 @enderror" 
                                           id="end_datetime" 
                                           name="end_datetime" 
                                           value="{{ old('end_datetime') }}">
                                    @error('end_datetime')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        Se remplira automatiquement (+2h) si laissé vide
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Localisation -->
                        <div class="space-y-6">
                            <div class="flex items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Localisation</h2>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="is_virtual" 
                                           name="is_virtual" 
                                           value="1" 
                                           {{ old('is_virtual') ? 'checked' : '' }}
                                           class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary dark:focus:ring-primary-dark dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="is_virtual" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        Événement virtuel (en ligne)
                                    </label>
                                </div>
                                
                                <div class="grid grid-cols-1 gap-4">
                                    <div id="location-field">
                                        <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Lieu de l'événement
                                        </label>
                                        <input type="text" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white placeholder-gray-400 transition-all duration-200 @error('location') border-red-500 @enderror" 
                                               id="location" 
                                               name="location" 
                                               value="{{ old('location') }}" 
                                               placeholder="Ex: Bibliothèque municipale, 12 rue de la République, Paris">
                                        @error('location')
                                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div id="meeting-link-field" class="hidden">
                                        <label for="meeting_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Lien de la réunion virtuelle
                                        </label>
                                        <input type="url" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white placeholder-gray-400 transition-all duration-200 @error('meeting_link') border-red-500 @enderror" 
                                               id="meeting_link" 
                                               name="meeting_link" 
                                               value="{{ old('meeting_link') }}" 
                                               placeholder="https://meet.google.com/... ou https://zoom.us/...">
                                        @error('meeting_link')
                                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 5: Paramètres d'inscription -->
                        <div class="space-y-6">
                            <div class="flex items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zm-13.5 0a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Gestion des participants</h2>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="max_participants" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nombre maximum de participants
                                    </label>
                                    <input type="number" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white placeholder-gray-400 transition-all duration-200 @error('max_participants') border-red-500 @enderror" 
                                           id="max_participants" 
                                           name="max_participants" 
                                           value="{{ old('max_participants') }}" 
                                           min="1" 
                                           placeholder="Ex: 20">
                                    @error('max_participants')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        Laisser vide pour un nombre illimité
                                    </p>
                                </div>
                                
                                <div class="flex items-center h-full pt-6">
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="requires_approval" 
                                               name="requires_approval" 
                                               value="1" 
                                               {{ old('requires_approval') ? 'checked' : '' }}
                                               class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary dark:focus:ring-primary-dark dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="requires_approval" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Approbation requise pour s'inscrire
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 6: Ressources et prérequis -->
                        <div class="space-y-6">
                            <div class="flex items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h8a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ressources et prérequis</h2>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                               
                                
                                <div>
                                    <label for="requirements" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        Prérequis
                                    </label>
                                    <textarea class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white placeholder-gray-400 resize-none transition-all duration-200 @error('requirements') border-red-500 @enderror" 
                                              id="requirements" 
                                              name="requirements" 
                                              rows="4" 
                                              placeholder="Exemple: Avoir lu le livre X, apporter son carnet de notes, niveau débutant en littérature...">{{ old('requirements') }}</textarea>
                                    @error('requirements')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        Quels sont les prérequis pour participer ?
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Section 7: Actions -->
                        <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <button type="button" 
                                        onclick="history.back()"
                                        class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Annuler
                                </button>
                                
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <button type="submit" 
                                            name="status" 
                                            value="draft" 
                                            class="inline-flex items-center px-6 py-3 border border-primary text-primary bg-white hover:bg-primary hover:text-white dark:bg-gray-800 dark:border-primary dark:text-primary dark:hover:bg-primary dark:hover:text-white font-medium rounded-lg transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                        </svg>
                                        Sauvegarder en brouillon
                                    </button>
                                    
                                    <button type="submit" 
                                            name="status" 
                                            value="published" 
                                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                        Publier l'événement
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar avec conseils -->
        <div class="space-y-6">
            <!-- Conseils pour créer un événement -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Conseils pour un événement réussi</h3>
                    </div>
                </div>
                
                <div class="p-6 space-y-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Titre accrocheur</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Choisissez un titre clair et engageant qui résume bien l'essence de votre événement.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Planification optimale</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Programmez votre événement au moins 3-7 jours à l'avance pour permettre aux participants de s'organiser.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Gestion des participants</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Estimez le nombre de participants pour mieux organiser l'espace et les ressources nécessaires.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Description détaillée</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Plus votre description est précise et inspirante, plus les participants seront motivés à participer.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Types d'événements disponibles -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Types d'événements</h3>
                    </div>
                </div>
                
                <div class="p-6 space-y-4">
                    @foreach(App\Models\GroupEvent::EVENT_TYPES as $key => $typeInfo)
                        <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex-shrink-0 mr-3">
                                @php
                                    $colorClasses = [
                                        'primary' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'success' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'danger' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'secondary' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                    ];
                                    $colorClass = $colorClasses[$typeInfo['color']] ?? $colorClasses['secondary'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                    {{ $typeInfo['icon'] }}
                                </span>
                            </div>
                            <div class="flex-grow">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">{{ $typeInfo['label'] }}</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $typeInfo['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isVirtualCheckbox = document.getElementById('is_virtual');
    const locationField = document.getElementById('location-field');
    const meetingLinkField = document.getElementById('meeting-link-field');
    
    function toggleFields() {
        if (isVirtualCheckbox.checked) {
            locationField.classList.add('hidden');
            meetingLinkField.classList.remove('hidden');
        } else {
            locationField.classList.remove('hidden');
            meetingLinkField.classList.add('hidden');
        }
    }
    
    isVirtualCheckbox.addEventListener('change', toggleFields);
    toggleFields(); // Initial call
    
    // Auto-fill end datetime when start datetime changes
    const startDatetime = document.getElementById('start_datetime');
    const endDatetime = document.getElementById('end_datetime');
    
    startDatetime.addEventListener('change', function() {
        if (!endDatetime.value && startDatetime.value) {
            const start = new Date(startDatetime.value);
            const end = new Date(start.getTime() + (2 * 60 * 60 * 1000)); // +2 hours
            
            const year = end.getFullYear();
            const month = String(end.getMonth() + 1).padStart(2, '0');
            const day = String(end.getDate()).padStart(2, '0');
            const hours = String(end.getHours()).padStart(2, '0');
            const minutes = String(end.getMinutes()).padStart(2, '0');
            
            endDatetime.value = `${year}-${month}-${day}T${hours}:${minutes}`;
        }
    });
    
    // Transform resources textarea into array
    const resourcesTextarea = document.getElementById('resources');
    const form = document.getElementById('eventForm');
    
    form.addEventListener('submit', function() {
        if (resourcesTextarea.value.trim()) {
            const resources = resourcesTextarea.value.split('\n')
                .map(item => item.trim())
                .filter(item => item.length > 0);
            resourcesTextarea.value = JSON.stringify(resources);
        }
    });
});
</script>
@endsection