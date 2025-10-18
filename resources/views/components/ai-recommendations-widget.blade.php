@php
    $user = auth()->user();
    $showRecommendations = $user && isset($showAiRecommendations) ? $showAiRecommendations : true;
@endphp

@if($user && $showRecommendations)
<div id="ai-recommendations-widget" class="bg-white rounded-lg shadow-lg p-6 mb-8">
    <!-- En-tête du widget -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-brain text-white text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800">Recommandations IA</h3>
                <p class="text-sm text-gray-600">Sélectionnées spécialement pour vous</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="refreshAIRecommendations()" 
                    class="text-gray-500 hover:text-blue-600 transition-colors"
                    title="Actualiser">
                <i class="fas fa-sync-alt"></i>
            </button>
            <a href="{{ route('ai.recommendations') }}" 
               class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                Voir tout
            </a>
        </div>
    </div>

    <!-- Conteneur des recommandations -->
    <div id="ai-recommendations-container" class="relative">
        <!-- Loading state -->
        <div id="ai-loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
            <span class="ml-3 text-gray-600">Génération des recommandations IA...</span>
        </div>
        
        <!-- Contenu des recommandations (chargé dynamiquement) -->
        <div id="ai-recommendations-content" class="hidden"></div>
        
        <!-- État d'erreur -->
        <div id="ai-error" class="hidden text-center py-8">
            <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-3"></i>
            <p class="text-gray-600 mb-4">Impossible de charger les recommandations</p>
            <button onclick="loadAIRecommendations()" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                <i class="fas fa-retry mr-2"></i>Réessayer
            </button>
        </div>
    </div>

    <!-- Indicateur de performance IA -->
    <div class="mt-4 pt-4 border-t border-gray-100">
        <div class="flex items-center justify-between text-xs text-gray-500">
            <div class="flex items-center">
                <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                <span>Système IA actif</span>
            </div>
            <div id="ai-accuracy" class="flex items-center">
                <span class="mr-1">Précision:</span>
                <span class="font-semibold text-green-600">--</span>
            </div>
            <div id="ai-learning" class="flex items-center">
                <i class="fas fa-graduation-cap mr-1"></i>
                <span>Apprentissage en cours...</span>
            </div>
        </div>
    </div>
</div>

<!-- Script du widget -->
<script>
let aiWidgetInitialized = false;

// Charger les recommandations IA
async function loadAIRecommendations() {
    const container = document.getElementById('ai-recommendations-content');
    const loading = document.getElementById('ai-loading');
    const error = document.getElementById('ai-error');
    
    // Afficher le loading
    loading.classList.remove('hidden');
    container.classList.add('hidden');
    error.classList.add('hidden');
    
    try {
        const response = await fetch('/api/ai/recommendations?limit=6', {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Authorization': 'Bearer ' + (localStorage.getItem('authToken') || '')
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success && data.data.recommendations.length > 0) {
            displayAIRecommendations(data.data.recommendations);
            updateAIMetrics(data.data);
        } else {
            showNoRecommendations();
        }
        
    } catch (error) {
        console.error('Erreur chargement recommandations IA:', error);
        showAIError();
    }
}

// Afficher les recommandations
function displayAIRecommendations(recommendations) {
    const container = document.getElementById('ai-recommendations-content');
    const loading = document.getElementById('ai-loading');
    
    const html = `
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            ${recommendations.map(rec => generateMiniRecommendationCard(rec)).join('')}
        </div>
    `;
    
    container.innerHTML = html;
    
    // Masquer loading et afficher contenu
    loading.classList.add('hidden');
    container.classList.remove('hidden');
    
    // Ajouter les événements
    attachWidgetEvents(container);
}

// Générer une mini-carte de recommandation
function generateMiniRecommendationCard(recommendation) {
    const book = recommendation.book;
    const score = Math.round(recommendation.score * 20);
    const reason = recommendation.reasons[0] || 'Recommandation personnalisée';
    
    return `
        <div class="ai-mini-card bg-gray-50 rounded-lg p-4 hover:shadow-md transition-all duration-300 cursor-pointer" 
             data-book-id="${book.id}"
             onclick="window.location.href='/livre/${book.id}'">
            <div class="flex items-start space-x-3">
                <div class="w-12 h-16 flex-shrink-0 relative">
                    ${book.image_path ? 
                        `<img src="/storage/${book.image_path}" alt="${book.title}" class="w-full h-full object-cover rounded">` :
                        `<div class="w-full h-full bg-gradient-to-br from-blue-200 to-purple-200 rounded flex items-center justify-center">
                            <i class="fas fa-book text-xs text-gray-500"></i>
                        </div>`
                    }
                    <div class="absolute -top-1 -right-1 bg-purple-600 text-white text-xs px-1 rounded-full">
                        ${score}%
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-semibold text-sm text-gray-800 line-clamp-1 mb-1">${book.title}</h4>
                    <p class="text-xs text-gray-600 mb-2">par ${book.author || 'Inconnu'}</p>
                    <p class="text-xs text-purple-600 line-clamp-2">${reason}</p>
                </div>
            </div>
            <div class="flex justify-end mt-2 space-x-1">
                <button onclick="event.stopPropagation(); feedbackMini(${book.id}, true)" 
                        class="text-green-600 hover:text-green-700 text-xs p-1">
                    <i class="fas fa-thumbs-up"></i>
                </button>
                <button onclick="event.stopPropagation(); feedbackMini(${book.id}, false)" 
                        class="text-red-600 hover:text-red-700 text-xs p-1">
                    <i class="fas fa-thumbs-down"></i>
                </button>
            </div>
        </div>
    `;
}

// Feedback mini
async function feedbackMini(bookId, helpful) {
    try {
        const response = await fetch('/api/ai/feedback', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                book_id: bookId,
                helpful: helpful
            })
        });
        
        if (response.ok) {
            // Animation de feedback
            const card = document.querySelector(`[data-book-id="${bookId}"]`);
            card.classList.add('opacity-75');
            
            // Petit message de confirmation
            showMiniNotification(helpful ? '👍 Merci !' : '👎 Noté');
        }
    } catch (error) {
        console.error('Erreur feedback:', error);
    }
}

// Afficher absence de recommandations
function showNoRecommendations() {
    const container = document.getElementById('ai-recommendations-content');
    const loading = document.getElementById('ai-loading');
    
    container.innerHTML = `
        <div class="text-center py-6">
            <i class="fas fa-magic text-3xl text-gray-300 mb-3"></i>
            <p class="text-gray-600 mb-2">Pas encore de recommandations</p>
            <p class="text-xs text-gray-500">Explorez quelques livres pour que notre IA apprenne vos goûts</p>
            <a href="/books" class="inline-block mt-3 text-blue-600 hover:text-blue-700 text-sm">
                <i class="fas fa-search mr-1"></i>Explorer les livres
            </a>
        </div>
    `;
    
    loading.classList.add('hidden');
    container.classList.remove('hidden');
}

// Afficher erreur
function showAIError() {
    const loading = document.getElementById('ai-loading');
    const error = document.getElementById('ai-error');
    
    loading.classList.add('hidden');
    error.classList.remove('hidden');
}

// Actualiser les recommandations
function refreshAIRecommendations() {
    // Animation du bouton
    const btn = event.target;
    btn.classList.add('animate-spin');
    
    // Vider le cache et recharger
    if (window.aiSystem) {
        window.aiSystem.loadRecommendations(6, true).then(() => {
            loadAIRecommendations();
        }).finally(() => {
            btn.classList.remove('animate-spin');
        });
    } else {
        setTimeout(() => {
            loadAIRecommendations();
            btn.classList.remove('animate-spin');
        }, 1000);
    }
}

// Mettre à jour les métriques IA
function updateAIMetrics(data) {
    const accuracyElement = document.getElementById('ai-accuracy');
    if (accuracyElement && data.accuracy) {
        accuracyElement.querySelector('.font-semibold').textContent = 
            Math.round(data.accuracy * 100) + '%';
    }
}

// Attacher les événements du widget
function attachWidgetEvents(container) {
    // Observer les cartes pour tracking
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const bookId = entry.target.getAttribute('data-book-id');
                if (bookId && window.aiSystem) {
                    window.aiSystem.recordInteraction(bookId, 'view', 0.3, {
                        source: 'widget_view'
                    });
                }
            }
        });
    }, { threshold: 0.5 });
    
    container.querySelectorAll('[data-book-id]').forEach(card => {
        observer.observe(card);
    });
}

// Mini notification
function showMiniNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed bottom-4 right-4 bg-gray-800 text-white px-3 py-2 rounded text-sm z-50';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 2000);
}

// Initialisation du widget
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('ai-recommendations-widget') && !aiWidgetInitialized) {
        aiWidgetInitialized = true;
        
        // Charger après un petit délai pour ne pas bloquer le rendu
        setTimeout(() => {
            loadAIRecommendations();
        }, 500);
    }
});
</script>

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.ai-mini-card:hover {
    transform: translateY(-2px);
}

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

.ai-mini-card {
    animation: fadeInUp 0.4s ease-out;
}
</style>
@endif