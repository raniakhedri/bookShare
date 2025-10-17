@extends('frontoffice.layouts.app')

@section('title', 'Mes Favoris')

@section('content')
<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-8">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- En-tête animé -->
        <div class="text-center mb-12 transform transition-all duration-500 starting:opacity-0 starting:translate-y-6">
            <h1 class="text-4xl lg:text-5xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                Ma Liste de Favoris
            </h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] max-w-2xl mx-auto">
                Gérez votre collection personnelle de livres favoris
            </p>
        </div>

        @if($favorites->count() > 0)
            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-[#161615] rounded-xl p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Total des favoris</p>
                            <p class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $favorites->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-[#161615] rounded-xl p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Disponibles</p>
                            <p class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                                {{ $favorites->filter(function($fav) { return $fav->book->availability; })->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#161615] rounded-xl p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Catégories</p>
                            <p class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                                {{ $favorites->pluck('book.category_id')->unique()->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grille de livres -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($favorites as $favorite)
                    <div class="book-card bg-white dark:bg-[#161615] rounded-xl overflow-hidden border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm hover:shadow-lg transition-all duration-300">
                        <div class="relative aspect-[3/4] overflow-hidden">
                            @if($favorite->book->image)
                                <img src="{{ asset('storage/' . $favorite->book->image) }}" 
                                     class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-500"
                                     alt="{{ $favorite->book->title }}">
                            @else
                                <div class="w-full h-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                            @endif
                            <!-- Badge de disponibilité -->
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $favorite->book->availability ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ $favorite->book->availability ? 'Disponible' : 'Indisponible' }}
                                </span>
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2 line-clamp-2">
                                {{ $favorite->book->title }}
                            </h3>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                {{ $favorite->book->author }}
                            </p>
                            @if($favorite->book->category)
                                <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                    {{ $favorite->book->category->name }}
                                </span>
                            @endif
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-3 line-clamp-3">
                                {{ Str::limit($favorite->book->description, 100) }}
                            </p>
                        </div>

                        <div class="p-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A] bg-gray-50 dark:bg-[#1D1D1B]">
                            <div class="flex justify-between items-center">
                                <a href="{{ route('frontoffice.book.show', $favorite->book->id) }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#f53003] dark:hover:text-[#FF4433] transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Voir
                                </a>
                                <button onclick="removeFavorite({{ $favorite->book->id }})" 
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                        data-book-id="{{ $favorite->book->id }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Retirer
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 px-4 bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <svg class="w-20 h-20 text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <h3 class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                    Aucun livre en favoris
                </h3>
                <p class="text-[#706f6c] dark:text-[#A1A09A] mb-6 text-center max-w-md">
                    Commencez à ajouter des livres à vos favoris pour les retrouver facilement plus tard.
                </p>
                <a href="{{ route('book') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#f53003] dark:bg-[#FF4433] text-white rounded-lg font-medium hover:bg-[#d42a03] dark:hover:bg-[#e5391b] transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Parcourir les livres
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function removeFavorite(bookId) {
    if (confirm('Êtes-vous sûr de vouloir retirer ce livre de vos favoris ?')) {
        fetch(`/books/${bookId}/favorite`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === false) {
                // Afficher une notification
                showNotification('Livre retiré des favoris', 'success');
                // Actualiser la page avec une animation de fondu
                fadeOutAndReload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Une erreur est survenue', 'error');
        });
    }
}

function showNotification(message, type) {
    const notif = document.createElement('div');
    notif.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg text-white transform translate-y-0 opacity-100 transition-all duration-500 ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' :
        'bg-blue-500'
    }`;
    notif.textContent = message;
    document.body.appendChild(notif);

    setTimeout(() => {
        notif.classList.add('translate-y-full', 'opacity-0');
        setTimeout(() => notif.remove(), 500);
    }, 3000);
}

function fadeOutAndReload() {
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity 0.5s ease';
    setTimeout(() => {
        location.reload();
    }, 500);
}
</script>
@endpush

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Animation d'apparition progressive */
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
    
    .book-card {
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
    }
    
    .book-card:nth-child(2) { animation-delay: 0.1s; }
    .book-card:nth-child(3) { animation-delay: 0.2s; }
    .book-card:nth-child(4) { animation-delay: 0.3s; }
</style>
@endpush
@endsection