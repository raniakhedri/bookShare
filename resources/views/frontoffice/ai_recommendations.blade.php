@extends('frontoffice.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="container mx-auto px-4">
        <!-- En-tête des recommandations IA -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-brain text-blue-600 mr-3"></i>
                    Recommandations IA Personnalisées
                </h1>
                <p class="text-gray-600 text-lg">
                    Découvrez des livres sélectionnés spécialement pour vous par notre intelligence artificielle
                </p>
            </div>
            
            <!-- Métriques utilisateur -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8">
                <div class="bg-blue-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $metrics['total_interactions'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Interactions totales</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $metrics['unique_books'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Livres uniques</div>
                </div>
                <div class="bg-purple-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ number_format(($metrics['preference_strength'] ?? 0) * 100, 1) }}%</div>
                    <div class="text-sm text-gray-600">Force des préférences</div>
                </div>
                <div class="bg-orange-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-orange-600">{{ number_format(($metrics['recommendation_accuracy'] ?? 0) * 100, 1) }}%</div>
                    <div class="text-sm text-gray-600">Précision IA</div>
                </div>
            </div>
        </div>

        <!-- Recommandations principales -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-magic text-purple-600 mr-3"></i>
                Recommandations Intelligentes
            </h2>
            
            @if($recommendations && $recommendations->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($recommendations as $recommendation)
                        @php
                            $book = $recommendation['book'];
                            $score = $recommendation['score'];
                            $reasons = $recommendation['reasons'];
                            $scorePercentage = min(100, $score * 20); // Convertir en pourcentage
                        @endphp
                        
                        <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden recommendation-card" data-book-id="{{ $book->id }}">
                            <!-- Image du livre -->
                            <div class="relative">
                                @if($book->image_path)
                                    <img src="{{ Storage::url($book->image_path) }}" alt="{{ $book->title }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                        <i class="fas fa-book text-4xl text-gray-500"></i>
                                    </div>
                                @endif
                                
                                <!-- Score IA -->
                                <div class="absolute top-2 right-2 bg-black bg-opacity-75 text-white px-2 py-1 rounded-full text-xs font-bold">
                                    IA: {{ number_format($scorePercentage, 0) }}%
                                </div>
                            </div>
                            
                            <!-- Contenu de la carte -->
                            <div class="p-4">
                                <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2">{{ $book->title }}</h3>
                                <p class="text-gray-600 text-sm mb-2">par {{ $book->author ?? 'Auteur inconnu' }}</p>
                                
                                @if($book->category)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mb-3">
                                        {{ $book->category->name }}
                                    </span>
                                @endif
                                
                                <!-- Raisons de la recommandation -->
                                <div class="mb-3">
                                    <p class="text-xs text-gray-500 mb-1">Pourquoi ce livre ?</p>
                                    @foreach(array_slice($reasons, 0, 2) as $reason)
                                        <div class="text-xs bg-yellow-50 text-yellow-800 px-2 py-1 rounded mb-1">
                                            {{ $reason }}
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('books.show', $book->id) }}" class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 transition-colors text-sm">
                                        <i class="fas fa-eye mr-1"></i> Voir
                                    </a>
                                    
                                    <div class="flex space-x-2">
                                        <button onclick="feedbackRecommendation({{ $book->id }}, true)" class="text-green-600 hover:text-green-700 feedback-btn" title="Utile">
                                            <i class="fas fa-thumbs-up"></i>
                                        </button>
                                        <button onclick="feedbackRecommendation({{ $book->id }}, false)" class="text-red-600 hover:text-red-700 feedback-btn" title="Pas utile">
                                            <i class="fas fa-thumbs-down"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Bouton pour actualiser les recommandations -->
                <div class="text-center mt-8">
                    <button onclick="refreshRecommendations()" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Actualiser les recommandations
                    </button>
                </div>
            @else
                <!-- Aucune recommandation -->
                <div class="text-center py-12">
                    <i class="fas fa-robot text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Pas encore de recommandations</h3>
                    <p class="text-gray-500 mb-6">Interagissez avec quelques livres pour que notre IA apprenne vos préférences</p>
                    <a href="{{ route('books.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        Explorer les livres
                    </a>
                </div>
            @endif
        </div>

        <!-- Préférences utilisateur -->
        @if($preferences && $preferences->count() > 0)
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-heart text-red-600 mr-3"></i>
                Vos Préférences Détectées
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($preferences->sortByDesc('preference_score')->take(9) as $preference)
                    @php
                        $scorePercentage = $preference->preference_score * 100;
                        $confidencePercentage = $preference->confidence_level * 100;
                    @endphp
                    
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-gray-800">{{ $preference->category->name ?? 'Catégorie inconnue' }}</h4>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ number_format($scorePercentage, 1) }}%</span>
                        </div>
                        
                        <!-- Barre de progression des préférences -->
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $scorePercentage }}%"></div>
                        </div>
                        
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>Confiance: {{ number_format($confidencePercentage, 0) }}%</span>
                            <span class="capitalize">{{ $preference->learning_source }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Notification toast -->
<div id="notification-toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50">
    <i class="fas fa-check-circle mr-2"></i>
    <span id="notification-message"></span>
</div>

@endsection

@push('scripts')
<script>
    // Enregistrer une interaction lors de la visualisation d'un livre
    function recordInteraction(bookId, interactionType, value = 1.0) {
        fetch('/api/ai/interaction', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Authorization': 'Bearer ' + (localStorage.getItem('authToken') || '')
            },
            body: JSON.stringify({
                book_id: bookId,
                interaction_type: interactionType,
                interaction_value: value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Interaction enregistrée:', data);
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

    // Feedback sur une recommandation
    function feedbackRecommendation(bookId, helpful) {
        fetch('/api/ai/feedback', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Authorization': 'Bearer ' + (localStorage.getItem('authToken') || '')
            },
            body: JSON.stringify({
                book_id: bookId,
                helpful: helpful
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'Merci pour votre feedback !');
                
                // Désactiver les boutons de feedback pour ce livre
                const card = document.querySelector(`[data-book-id="${bookId}"]`);
                if (card) {
                    const buttons = card.querySelectorAll('.feedback-btn');
                    buttons.forEach(btn => {
                        btn.disabled = true;
                        btn.classList.add('opacity-50', 'cursor-not-allowed');
                    });
                }
            } else {
                showNotification('Erreur lors de l\'envoi du feedback', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur lors de l\'envoi du feedback', 'error');
        });
    }

    // Actualiser les recommandations
    function refreshRecommendations() {
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Actualisation...';
        button.disabled = true;

        // Vider le cache côté serveur et recharger
        fetch('/ai/recommendations/refresh', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(() => {
            location.reload();
        })
        .catch(error => {
            console.error('Erreur:', error);
            button.innerHTML = originalText;
            button.disabled = false;
            showNotification('Erreur lors de l\'actualisation', 'error');
        });
    }

    // Afficher une notification
    function showNotification(message, type = 'success') {
        const toast = document.getElementById('notification-toast');
        const messageElement = document.getElementById('notification-message');
        
        messageElement.textContent = message;
        
        // Changer la couleur selon le type
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg transform transition-transform duration-300 z-50 ${
            type === 'error' ? 'bg-red-500' : 'bg-green-500'
        } text-white`;
        
        // Afficher
        toast.classList.remove('translate-x-full');
        
        // Masquer après 3 secondes
        setTimeout(() => {
            toast.classList.add('translate-x-full');
        }, 3000);
    }

    // Enregistrer les vues des recommandations au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        const recommendationCards = document.querySelectorAll('.recommendation-card');
        
        recommendationCards.forEach(card => {
            const bookId = card.getAttribute('data-book-id');
            if (bookId) {
                recordInteraction(bookId, 'view', 0.5);
            }
        });

        // Observer pour les recommandations qui entrent dans le viewport
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const bookId = entry.target.getAttribute('data-book-id');
                    if (bookId) {
                        recordInteraction(bookId, 'view', 0.8);
                    }
                }
            });
        }, { threshold: 0.5 });

        recommendationCards.forEach(card => {
            observer.observe(card);
        });
    });
</script>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .recommendation-card {
        transition: all 0.3s ease;
    }

    .recommendation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }

    .loading {
        animation: pulse 1s infinite;
    }
</style>
@endpush