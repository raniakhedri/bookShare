@extends('frontoffice.layouts.app')

@section('title', $group->name . ' - Bookly')

@section('content')
<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-8">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-8">
            <a href="{{ route('groups.index') }}" class="hover:text-primary transition-colors">Groupes</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $group->name }}</span>
        </nav>

        <!-- Group Header -->
        <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <!-- Cover Image or Gradient -->
            <div class="h-48 bg-gradient-to-r from-primary/20 to-primary/30 relative">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="absolute top-6 right-6">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-white/90 text-gray-800 backdrop-blur-sm">
                        @if($group->is_public ?? true)
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Public
                        @else
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Privé
                        @endif
                    </span>
                </div>
            </div>

            <!-- Group Info -->
            <div class="p-8">
                <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                    <!-- Group Avatar -->
                    <div class="flex-shrink-0">
                        @if($group->image)
                            <img src="{{ asset('storage/' . $group->image) }}" 
                                 alt="{{ $group->name }}" 
                                 class="w-24 h-24 lg:w-32 lg:h-32 rounded-2xl object-cover border-4 border-white dark:border-gray-700 shadow-lg -mt-16">
                        @else
                            <div class="w-24 h-24 lg:w-32 lg:h-32 rounded-2xl bg-gradient-to-br from-primary/20 to-primary/40 flex items-center justify-center border-4 border-white dark:border-gray-700 shadow-lg -mt-16">
                                <span class="text-4xl lg:text-5xl">📚</span>
                            </div>
                        @endif
                    </div>

                    <!-- Group Details -->
                    <div class="flex-grow">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div>
                                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                                    {{ $group->name }}
                                </h1>
                                <div class="flex items-center gap-4 mb-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary/10 text-primary">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        {{ $group->theme }}
                                    </span>
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">
                                        {{ $group->members->count() }} membre{{ $group->members->count() > 1 ? 's' : '' }}
                                    </span>
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">
                                        Créé {{ $group->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                @if($group->description)
                                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                        {{ $group->description }}
                                    </p>
                                @endif
                            </div>

                            <!-- Action Button -->
                            <div class="flex-shrink-0">
                                @php
                                    $isMember = false;
                                    $isPending = false;
                                    if(auth()->check()) {
                                        $pivot = $group->users->where('id', auth()->id())->first();
                                        if($pivot) {
                                            $status = $pivot->pivot->status;
                                            $isMember = ($status === 'accepted');
                                            $isPending = ($status === 'pending');
                                        }
                                    }
                                @endphp

                                @if(!auth()->check())
                                    <a href="{{ route('login') }}" 
                                       class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        Se connecter
                                    </a>
                                @elseif($isMember)
                                    <a href="{{ route('frontoffice.group.wall', $group->id) }}" 
                                       class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary-dark text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-6a2 2 0 012-2h8V8z"></path>
                                        </svg>
                                        Accéder au groupe
                                    </a>
                                @elseif($isPending)
                                    <div class="inline-flex items-center px-6 py-3 bg-yellow-100 text-yellow-800 font-medium rounded-lg">
                                        <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Demande en attente
                                    </div>
                                @else
                                    <form action="{{ route('groups.join', $group->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                            </svg>
                                            Rejoindre le groupe
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Recent Activity -->
                @if($group->posts->count() > 0)
                    <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-6 h-6 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1l-4 4z"></path>
                                </svg>
                                Activité récente
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                @foreach($group->posts->take(3) as $post)
                                    <div class="flex items-start space-x-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                                        <div class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-grow">
                                            <div class="flex items-center gap-2 mb-2">
                                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $post->user->name }}</h4>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-gray-600 dark:text-gray-300 line-clamp-3">{{ $post->content }}</p>
                                            @if($post->comments->count() > 0)
                                                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $post->comments->count() }} commentaire{{ $post->comments->count() > 1 ? 's' : '' }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($isMember)
                                <div class="mt-6 text-center">
                                    <a href="{{ route('frontoffice.group.wall', $group->id) }}" 
                                       class="inline-flex items-center text-primary hover:text-primary-dark font-medium">
                                        Voir toute l'activité
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Upcoming Events -->
                @if($group->upcomingEvents()->count() > 0)
                    <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-6 h-6 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Événements à venir
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($group->upcomingEvents()->take(3)->get() as $event)
                                    <div class="flex items-start space-x-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                                                <span class="text-lg">{{ \App\Models\GroupEvent::EVENT_TYPES[$event->type]['icon'] ?? '📅' }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-grow">
                                            <h4 class="font-medium text-gray-900 dark:text-white mb-1">{{ $event->title }}</h4>
                                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $event->start_datetime->format('d/m/Y à H:i') }}
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">{{ $event->description }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Members -->
                <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zm-13.5 0a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Membres ({{ $group->members->count() }})
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($group->members->take(6) as $member)
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white text-sm">{{ $member->name }}</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Membre depuis {{ $member->pivot->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($group->members->count() > 6)
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
                                    et {{ $group->members->count() - 6 }} autre{{ $group->members->count() - 6 > 1 ? 's' : '' }} membre{{ $group->members->count() - 6 > 1 ? 's' : '' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Group Stats -->
                <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Statistiques</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Publications</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $group->posts->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Événements</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $group->events()->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Badges attribués</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $group->badges()->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }
    
    .line-clamp-3 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
    }
</style>
@endpush