/**
 * AI Recommendation System JavaScript
 * Système intelligent de recommandations avec machine learning côté client
 */

class AIRecommendationSystem {
    constructor(options = {}) {
        this.apiUrl = options.apiUrl || '/api/ai';
        this.csrfToken = options.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content;
        this.authToken = options.authToken || localStorage.getItem('authToken');
        this.userId = options.userId || null;
        
        // Configuration IA
        this.config = {
            minInteractionTime: 3000, // Temps minimum pour considérer une lecture (3s)
            trackingEnabled: true,
            batchSize: 10, // Nombre d'interactions avant envoi groupé
            cacheDuration: 300000, // Cache des recommandations (5min)
            ...options.config
        };
        
        // État du système
        this.state = {
            currentBook: null,
            startTime: null,
            interactions: [],
            recommendations: [],
            preferences: {},
            isTracking: false
        };
        
        this.init();
    }

    /**
     * Initialisation du système IA
     */
    init() {
        console.log('🤖 AI Recommendation System initialisé');
        
        // Charger les données utilisateur
        this.loadUserData();
        
        // Démarrer le tracking automatique
        if (this.config.trackingEnabled) {
            this.startAutoTracking();
        }
        
        // Écouter les événements de la page
        this.setupEventListeners();
        
        // Charger les recommandations en cache
        this.loadCachedRecommendations();
    }

    /**
     * Configuration des écouteurs d'événements
     */
    setupEventListeners() {
        // Tracking de la lecture
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.pauseTracking();
            } else {
                this.resumeTracking();
            }
        });

        // Tracking des clics sur les livres
        document.addEventListener('click', (e) => {
            const bookElement = e.target.closest('[data-book-id]');
            if (bookElement) {
                const bookId = bookElement.getAttribute('data-book-id');
                const action = this.getActionFromElement(e.target);
                this.recordInteraction(bookId, action);
            }
        });

        // Tracking du scroll (engagement)
        let scrollTimeout;
        document.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                this.recordScrollEngagement();
            }, 1000);
        });

        // Décharger les données avant fermeture
        window.addEventListener('beforeunload', () => {
            this.flushInteractions();
        });
    }

    /**
     * Démarrer le tracking automatique
     */
    startAutoTracking() {
        if (this.state.isTracking) return;
        
        this.state.isTracking = true;
        this.state.startTime = Date.now();
        
        // Détecter le livre actuel
        const currentBookElement = document.querySelector('[data-current-book-id]');
        if (currentBookElement) {
            this.state.currentBook = currentBookElement.getAttribute('data-current-book-id');
        }

        console.log('📊 Tracking automatique démarré');
    }

    /**
     * Pauser le tracking
     */
    pauseTracking() {
        if (!this.state.isTracking) return;
        
        this.recordCurrentReadingTime();
        this.state.isTracking = false;
        console.log('⏸️ Tracking mis en pause');
    }

    /**
     * Reprendre le tracking
     */
    resumeTracking() {
        if (this.state.isTracking) return;
        
        this.state.startTime = Date.now();
        this.state.isTracking = true;
        console.log('▶️ Tracking repris');
    }

    /**
     * Enregistrer une interaction
     */
    recordInteraction(bookId, interactionType, value = 1.0, context = {}) {
        const interaction = {
            book_id: parseInt(bookId),
            interaction_type: interactionType,
            interaction_value: value,
            duration_seconds: context.duration || null,
            context_data: {
                timestamp: Date.now(),
                page_url: window.location.href,
                user_agent: navigator.userAgent.substring(0, 100),
                viewport: `${window.innerWidth}x${window.innerHeight}`,
                ...context
            },
            timestamp: new Date().toISOString()
        };

        this.state.interactions.push(interaction);
        
        console.log(`📝 Interaction enregistrée: ${interactionType} pour le livre ${bookId}`);

        // Envoi groupé si nécessaire
        if (this.state.interactions.length >= this.config.batchSize) {
            this.flushInteractions();
        }

        // Mise à jour des préférences locales
        this.updateLocalPreferences(bookId, interactionType, value);
    }

    /**
     * Enregistrer le temps de lecture actuel
     */
    recordCurrentReadingTime() {
        if (!this.state.currentBook || !this.state.startTime) return;
        
        const duration = Math.floor((Date.now() - this.state.startTime) / 1000);
        
        if (duration >= this.config.minInteractionTime / 1000) {
            this.recordInteraction(
                this.state.currentBook, 
                'read_time', 
                Math.min(10, duration / 60), // Score basé sur minutes (max 10)
                { duration: duration }
            );
        }
    }

    /**
     * Enregistrer l'engagement par scroll
     */
    recordScrollEngagement() {
        const scrollPercentage = Math.round(
            (window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100
        );
        
        if (scrollPercentage > 50 && this.state.currentBook) {
            this.recordInteraction(
                this.state.currentBook, 
                'view', 
                scrollPercentage / 100,
                { scroll_percentage: scrollPercentage }
            );
        }
    }

    /**
     * Déterminer l'action à partir de l'élément cliqué
     */
    getActionFromElement(element) {
        if (element.classList.contains('favorite-btn')) return 'like';
        if (element.classList.contains('download-btn')) return 'download';
        if (element.classList.contains('share-btn')) return 'share';
        if (element.classList.contains('bookmark-btn')) return 'bookmark';
        if (element.classList.contains('read-btn')) return 'view';
        if (element.closest('a[href*="books"]')) return 'view';
        
        return 'view';
    }

    /**
     * Vider les interactions en attente
     */
    async flushInteractions() {
        if (this.state.interactions.length === 0) return;
        
        const interactions = [...this.state.interactions];
        this.state.interactions = [];

        try {
            for (const interaction of interactions) {
                await this.sendInteraction(interaction);
            }
            console.log(`✅ ${interactions.length} interactions envoyées`);
        } catch (error) {
            console.error('❌ Erreur envoi interactions:', error);
            // Remettre en file d'attente en cas d'erreur
            this.state.interactions.unshift(...interactions);
        }
    }

    /**
     * Envoyer une interaction à l'API
     */
    async sendInteraction(interaction) {
        const response = await fetch(`${this.apiUrl}/interaction`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                ...(this.authToken && { 'Authorization': `Bearer ${this.authToken}` })
            },
            body: JSON.stringify(interaction)
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        return response.json();
    }

    /**
     * Charger les recommandations
     */
    async loadRecommendations(limit = 10, forceRefresh = false) {
        const cacheKey = `ai_recommendations_${this.userId}`;
        const cached = localStorage.getItem(cacheKey);
        
        if (!forceRefresh && cached) {
            const { data, timestamp } = JSON.parse(cached);
            if (Date.now() - timestamp < this.config.cacheDuration) {
                this.state.recommendations = data;
                console.log('📚 Recommandations chargées depuis le cache');
                return data;
            }
        }

        try {
            const response = await fetch(`${this.apiUrl}/recommendations?limit=${limit}`, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    ...(this.authToken && { 'Authorization': `Bearer ${this.authToken}` })
                }
            });

            if (response.ok) {
                const result = await response.json();
                this.state.recommendations = result.data.recommendations;
                
                // Mise en cache
                localStorage.setItem(cacheKey, JSON.stringify({
                    data: this.state.recommendations,
                    timestamp: Date.now()
                }));

                console.log(`🎯 ${this.state.recommendations.length} nouvelles recommandations chargées`);
                return this.state.recommendations;
            }
        } catch (error) {
            console.error('❌ Erreur chargement recommandations:', error);
        }

        return [];
    }

    /**
     * Afficher les recommandations dans un conteneur
     */
    async displayRecommendations(containerId, options = {}) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const recommendations = await this.loadRecommendations(options.limit || 6);
        
        if (recommendations.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-robot text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">Pas encore de recommandations IA.</p>
                    <p class="text-sm text-gray-500">Interagissez avec des livres pour que notre IA apprenne vos préférences.</p>
                </div>
            `;
            return;
        }

        const html = recommendations.map(rec => this.generateRecommendationCard(rec)).join('');
        container.innerHTML = `
            <div class="ai-recommendations-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                ${html}
            </div>
        `;

        // Ajouter les interactions aux nouvelles cartes
        this.attachRecommendationEvents(container);
    }

    /**
     * Générer une carte de recommandation
     */
    generateRecommendationCard(recommendation) {
        const book = recommendation.book;
        const score = Math.round(recommendation.score * 20); // Score en pourcentage
        const reasons = recommendation.reasons.slice(0, 2);

        return `
            <div class="ai-recommendation-card bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300" 
                 data-book-id="${book.id}">
                <div class="relative">
                    ${book.image_path ? 
                        `<img src="/storage/${book.image_path}" alt="${book.title}" class="w-full h-48 object-cover rounded-t-lg">` :
                        `<div class="w-full h-48 bg-gradient-to-br from-blue-200 to-purple-200 rounded-t-lg flex items-center justify-center">
                            <i class="fas fa-book text-4xl text-gray-500"></i>
                        </div>`
                    }
                    <div class="absolute top-2 right-2 bg-black bg-opacity-75 text-white px-2 py-1 rounded-full text-xs font-bold">
                        IA: ${score}%
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-2 line-clamp-2">${book.title}</h3>
                    <p class="text-gray-600 text-sm mb-3">par ${book.author || 'Auteur inconnu'}</p>
                    
                    <div class="mb-3">
                        ${reasons.map(reason => `
                            <div class="text-xs bg-yellow-50 text-yellow-800 px-2 py-1 rounded mb-1">
                                ${reason}
                            </div>
                        `).join('')}
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <a href="/livre/${book.id}" class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 transition-colors text-sm read-btn">
                            <i class="fas fa-eye mr-1"></i> Lire
                        </a>
                        <div class="flex space-x-2">
                            <button class="text-green-600 hover:text-green-700 feedback-btn" 
                                    onclick="aiSystem.feedbackRecommendation(${book.id}, true)" 
                                    title="Utile">
                                <i class="fas fa-thumbs-up"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-700 feedback-btn" 
                                    onclick="aiSystem.feedbackRecommendation(${book.id}, false)" 
                                    title="Pas utile">
                                <i class="fas fa-thumbs-down"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Attacher les événements aux cartes de recommandations
     */
    attachRecommendationEvents(container) {
        container.querySelectorAll('.ai-recommendation-card').forEach(card => {
            const bookId = card.getAttribute('data-book-id');
            
            // Observer l'intersection pour tracker les vues
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.recordInteraction(bookId, 'view', 0.8, { source: 'ai_recommendation' });
                    }
                });
            }, { threshold: 0.5 });
            
            observer.observe(card);
        });
    }

    /**
     * Feedback sur une recommandation
     */
    async feedbackRecommendation(bookId, helpful) {
        try {
            const response = await fetch(`${this.apiUrl}/feedback`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    ...(this.authToken && { 'Authorization': `Bearer ${this.authToken}` })
                },
                body: JSON.stringify({
                    book_id: bookId,
                    helpful: helpful
                })
            });

            if (response.ok) {
                this.showNotification('Merci pour votre feedback ! 🤖');
                
                // Désactiver les boutons de feedback
                document.querySelectorAll(`[data-book-id="${bookId}"] .feedback-btn`).forEach(btn => {
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                });
            }
        } catch (error) {
            console.error('❌ Erreur feedback:', error);
        }
    }

    /**
     * Charger les données utilisateur
     */
    async loadUserData() {
        try {
            const response = await fetch(`${this.apiUrl}/preferences`, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    ...(this.authToken && { 'Authorization': `Bearer ${this.authToken}` })
                }
            });

            if (response.ok) {
                const result = await response.json();
                this.state.preferences = result.data.preferences;
                console.log('👤 Données utilisateur chargées');
            }
        } catch (error) {
            console.error('❌ Erreur chargement données utilisateur:', error);
        }
    }

    /**
     * Charger les recommandations en cache
     */
    loadCachedRecommendations() {
        const cacheKey = `ai_recommendations_${this.userId}`;
        const cached = localStorage.getItem(cacheKey);
        
        if (cached) {
            try {
                const { data, timestamp } = JSON.parse(cached);
                if (Date.now() - timestamp < this.config.cacheDuration) {
                    this.state.recommendations = data;
                    console.log('💾 Recommandations en cache trouvées');
                }
            } catch (error) {
                console.error('❌ Erreur cache recommandations:', error);
            }
        }
    }

    /**
     * Mettre à jour les préférences locales
     */
    updateLocalPreferences(bookId, interactionType, value) {
        // Logique simple de mise à jour des préférences côté client
        // (peut être étendue avec des algorithmes plus sophistiqués)
        
        const weight = this.getInteractionWeight(interactionType);
        const localPrefs = JSON.parse(localStorage.getItem('local_preferences') || '{}');
        
        if (!localPrefs[bookId]) {
            localPrefs[bookId] = { score: 0, interactions: 0 };
        }
        
        localPrefs[bookId].score = (localPrefs[bookId].score + (value * weight)) / 2;
        localPrefs[bookId].interactions++;
        
        localStorage.setItem('local_preferences', JSON.stringify(localPrefs));
    }

    /**
     * Obtenir le poids d'une interaction
     */
    getInteractionWeight(interactionType) {
        const weights = {
            'view': 1,
            'like': 3,
            'share': 5,
            'download': 7,
            'read_time': 2,
            'search': 0.5,
            'rate': 4,
            'comment': 6,
            'bookmark': 8,
            'wishlist': 9
        };
        
        return weights[interactionType] || 1;
    }

    /**
     * Afficher une notification
     */
    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 text-white transform translate-x-full transition-transform duration-300 ${
            type === 'error' ? 'bg-red-500' : 'bg-green-500'
        }`;
        notification.innerHTML = `<i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'} mr-2"></i>${message}`;
        
        document.body.appendChild(notification);
        
        setTimeout(() => notification.classList.remove('translate-x-full'), 100);
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    /**
     * Obtenir les statistiques d'utilisation
     */
    getUsageStats() {
        return {
            totalInteractions: this.state.interactions.length,
            currentBook: this.state.currentBook,
            trackingActive: this.state.isTracking,
            recommendationsCount: this.state.recommendations.length,
            preferencesCount: Object.keys(this.state.preferences).length
        };
    }

    /**
     * Nettoyer et arrêter le système
     */
    destroy() {
        this.pauseTracking();
        this.flushInteractions();
        console.log('🤖 Système IA arrêté');
    }
}

// CSS pour les styles des recommandations
const aiStyles = `
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.ai-recommendation-card {
    transition: all 0.3s ease;
}

.ai-recommendation-card:hover {
    transform: translateY(-5px);
}

.ai-recommendations-grid {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
`;

// Injecter les styles
if (!document.querySelector('#ai-recommendation-styles')) {
    const styleElement = document.createElement('div');
    styleElement.id = 'ai-recommendation-styles';
    styleElement.innerHTML = aiStyles;
    document.head.appendChild(styleElement);
}

// Instance globale
window.aiSystem = null;

// Initialisation automatique
document.addEventListener('DOMContentLoaded', function() {
    // Configuration depuis les meta tags
    const userId = document.querySelector('meta[name="user-id"]')?.content;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    if (userId && csrfToken) {
        window.aiSystem = new AIRecommendationSystem({
            userId: userId,
            csrfToken: csrfToken,
            config: {
                trackingEnabled: true,
                minInteractionTime: 3000,
                batchSize: 5
            }
        });
        
        console.log('🚀 Système de recommandation IA initialisé');
    } else {
        console.log('⚠️ Utilisateur non connecté - Système IA désactivé');
    }
});

export default AIRecommendationSystem;