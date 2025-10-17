@extends('frontoffice.layouts.app')
@section('title', 'Événements - ' . $group->name . ' - Bookly')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-6">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('frontoffice.group.wall', $group->id) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                    ← Retour au groupe
                </a>
                <a href="{{ route('groups.events.create', $group->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors">
                    <span class="text-lg">➕</span>
                    Créer un événement
                </a>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center">
                    <span class="text-white text-2xl">📅</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Événements de {{ $group->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400">Découvrez et participez aux événements du groupe</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Événements à venir -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="text-2xl">🚀</span>
                        Événements à venir
                    </h2>
                    
                    @forelse($upcomingEvents as $event)
                    <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 border border-indigo-200 dark:border-indigo-700">
                        <div class="flex items-start gap-4">
                            <!-- Date -->
                            <div class="bg-white dark:bg-gray-700 rounded-lg p-3 text-center shadow-sm border border-gray-200 dark:border-gray-600 flex-shrink-0">
                                <div class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase">
                                    {{ $event->start_datetime->format('M') }}
                                </div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $event->start_datetime->format('d') }}
                                </div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ $event->start_datetime->format('H:i') }}
                                </div>
                            </div>

                            <!-- Contenu -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-2xl">{{ $event->type_details['icon'] }}</span>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $event->title }}</h3>
                                    <span class="text-xs px-2 py-1 rounded-full" style="background-color: {{ $event->type_details['color'] }}20; color: {{ $event->type_details['color'] }}">
                                        {{ $event->type_details['label'] }}
                                    </span>
                                </div>

                                <p class="text-gray-700 dark:text-gray-300 text-sm mb-3 line-clamp-2">
                                    {{ $event->description }}
                                </p>

                                <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    @if($event->location)
                                    <div class="flex items-center gap-1">
                                        <span>📍</span>
                                        <span>{{ $event->location }}</span>
                                    </div>
                                    @endif
                                    @if($event->is_virtual)
                                    <div class="flex items-center gap-1">
                                        <span>💻</span>
                                        <span>Virtuel</span>
                                    </div>
                                    @endif
                                    <div class="flex items-center gap-1">
                                        <span>⏱️</span>
                                        <span>{{ $event->formatted_duration }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span>👥</span>
                                        <span>{{ $event->approvedParticipants()->count() }} participant(s)</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        @if($event->user_registration_status)
                                            @if($event->user_registration_status === 'pending')
                                                <span class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-xs px-3 py-1 rounded-full">
                                                    ⏳ En attente
                                                </span>
                                            @elseif(in_array($event->user_registration_status, ['approved', 'confirmed']))
                                                <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs px-3 py-1 rounded-full">
                                                    ✅ Inscrit
                                                </span>
                                            @endif
                                        @elseif($event->is_full)
                                            <span class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 text-xs px-3 py-1 rounded-full">
                                                🚫 Complet
                                            </span>
                                        @else
                                            <button onclick="registerToEvent({{ $event->id }})" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-3 py-1 rounded-full transition-colors">
                                                S'inscrire
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <a href="{{ route('groups.events.show', [$group->id, $event->id]) }}" class="text-indigo-600 dark:text-indigo-400 text-sm font-medium hover:underline">
                                        Voir détails →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                        <div class="text-6xl mb-4">📅</div>
                        <h3 class="text-lg font-semibold mb-2">Aucun événement à venir</h3>
                        <p class="text-sm mb-4">Soyez le premier à organiser un événement pour ce groupe !</p>
                        <a href="{{ route('groups.events.create', $group->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center gap-2 transition-colors">
                            <span class="text-lg">➕</span>
                            Créer un événement
                        </a>
                    </div>
                    @endforelse
                </div>

                <!-- Événements passés -->
                @if($pastEvents->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6 mt-8">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="text-2xl">📚</span>
                        Événements passés
                    </h2>
                    
                    <div class="space-y-4">
                        @foreach($pastEvents as $event)
                        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl opacity-75">{{ $event->type_details['icon'] }}</span>
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $event->title }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $event->start_datetime->format('d/m/Y à H:i') }}
                                            • {{ $event->approvedParticipants()->count() }} participant(s)
                                        </p>
                                    </div>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full {{ $event->status === 'completed' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                    {{ $event->status_label }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Types d'événements -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="text-xl">🎯</span>
                        Types d'événements
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($eventTypes as $type => $details)
                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-700 text-center">
                            <div class="text-2xl mb-1">{{ $details['icon'] }}</div>
                            <div class="text-xs font-medium" style="color: {{ $details['color'] }}">
                                {{ $details['label'] }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="text-xl">📊</span>
                        Statistiques
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Événements à venir</span>
                            <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $upcomingEvents->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Événements passés</span>
                            <span class="font-semibold text-gray-600 dark:text-gray-400">{{ $pastEvents->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Mes inscriptions</span>
                            <span class="font-semibold text-green-600 dark:text-green-400">
                                {{ auth()->user()->eventParticipations()->whereHas('event', function($q) use ($group) { $q->where('group_id', $group->id); })->approved()->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function registerToEvent(eventId) {
    try {
        const response = await fetch(`/groups/{{ $group->id }}/events/${eventId}/register`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showNotification(data.error || 'Erreur lors de l\'inscription', 'error');
        }
    } catch (error) {
        showNotification('Erreur lors de l\'inscription', 'error');
    }
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    
    notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300`;
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <span>${type === 'success' ? '✅' : '❌'}</span>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush