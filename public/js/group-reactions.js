// Système de réactions pour les groupes
class GroupReactionSystem {
    constructor() {
        this.reactionTypes = {
            'like': { emoji: '👍', label: 'J\'aime', color: '#3B82F6' },
            'love': { emoji: '❤️', label: 'J\'adore', color: '#EF4444' },
            'laugh': { emoji: '😂', label: 'Drôle', color: '#F59E0B' },
            'wow': { emoji: '😮', label: 'Surprenant', color: '#8B5CF6' },
            'sad': { emoji: '😢', label: 'Triste', color: '#6B7280' },
            'angry': { emoji: '😠', label: 'En colère', color: '#DC2626' },
            'celebrate': { emoji: '🎉', label: 'Célébrer', color: '#10B981' }
        };
        
        this.initializeReactions();
    }

    initializeReactions() {
        document.addEventListener('DOMContentLoaded', () => {
            this.bindReactionEvents();
        });
    }

    bindReactionEvents() {
        // Événements pour les posts
        document.querySelectorAll('.reaction-trigger').forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                this.showReactionPicker(trigger);
            });
        });

        // Événements pour les réactions directes
        document.querySelectorAll('.reaction-button').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleReaction(button);
            });
        });
    }

    showReactionPicker(trigger) {
        const existingPicker = document.querySelector('.reaction-picker');
        if (existingPicker) {
            existingPicker.remove();
        }

        const picker = this.createReactionPicker(trigger);
        document.body.appendChild(picker);

        // Positionner le picker
        const rect = trigger.getBoundingClientRect();
        picker.style.position = 'absolute';
        picker.style.top = (rect.top - picker.offsetHeight - 10) + 'px';
        picker.style.left = (rect.left + (rect.width / 2) - (picker.offsetWidth / 2)) + 'px';
        picker.style.zIndex = '1000';

        // Fermer le picker si on clique ailleurs
        setTimeout(() => {
            document.addEventListener('click', (e) => {
                if (!picker.contains(e.target)) {
                    picker.remove();
                }
            }, { once: true });
        }, 100);
    }

    createReactionPicker(trigger) {
        const picker = document.createElement('div');
        picker.className = 'reaction-picker bg-white rounded-lg shadow-lg border border-gray-200 p-2 flex space-x-1';
        
        Object.entries(this.reactionTypes).forEach(([type, details]) => {
            const button = document.createElement('button');
            button.className = 'reaction-option hover:bg-gray-100 rounded-full p-2 transition-all duration-200 hover:scale-110';
            button.innerHTML = `
                <span class="text-2xl">${details.emoji}</span>
            `;
            button.title = details.label;
            button.dataset.reactionType = type;
            button.dataset.targetType = trigger.dataset.targetType;
            button.dataset.targetId = trigger.dataset.targetId;
            
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleReaction(button);
                picker.remove();
            });
            
            picker.appendChild(button);
        });

        return picker;
    }

    async handleReaction(button) {
        const targetType = button.dataset.targetType;
        const targetId = button.dataset.targetId;
        const reactionType = button.dataset.reactionType;

        try {
            button.classList.add('animate-pulse');
            
            const endpoint = targetType === 'post' 
                ? `/posts/${targetId}/react`
                : `/comments/${targetId}/react`;

            // Récupérer le token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                throw new Error('Token CSRF non trouvé');
            }

            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ reaction_type: reactionType })
            });

            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Réponse invalide du serveur');
            }

            const data = await response.json();

            if (data.success) {
                this.updateReactionDisplay(targetType, targetId, data);
                this.showFeedback(reactionType, data.action);
            } else {
                throw new Error(data.message || 'Erreur lors de la réaction');
            }

        } catch (error) {
            console.error('Erreur détaillée:', error);
            this.showError(`Erreur: ${error.message}`);
        } finally {
            button.classList.remove('animate-pulse');
        }
    }

    updateReactionDisplay(targetType, targetId, data) {
        const container = document.querySelector(`[data-${targetType}-id="${targetId}"]`);
        if (!container) return;

        const reactionsContainer = container.querySelector('.reactions-display');
        if (!reactionsContainer) return;

        // Mettre à jour l'affichage des réactions
        this.renderReactions(reactionsContainer, data.reactions);

        // Mettre à jour le bouton de l'utilisateur
        const userButton = container.querySelector('.user-reaction-status');
        if (userButton) {
            if (data.user_reaction) {
                const reactionDetails = this.reactionTypes[data.user_reaction];
                userButton.innerHTML = `${reactionDetails.emoji} ${reactionDetails.label}`;
                userButton.style.color = reactionDetails.color;
                userButton.classList.add('font-semibold');
            } else {
                userButton.innerHTML = '👍 Réagir';
                userButton.style.color = '#6B7280';
                userButton.classList.remove('font-semibold');
            }
        }
    }

    renderReactions(container, reactions) {
        container.innerHTML = '';

        if (reactions.total === 0) {
            container.innerHTML = '<span class="text-gray-500 text-sm">Aucune réaction</span>';
            return;
        }

        // Afficher les réactions les plus populaires
        const topReactions = Object.entries(reactions.types)
            .filter(([type, data]) => data.count > 0)
            .sort((a, b) => b[1].count - a[1].count)
            .slice(0, 3);

        const reactionsHtml = topReactions.map(([type, data]) => {
            return `
                <span class="inline-flex items-center space-x-1 text-sm">
                    <span class="text-lg">${data.emoji}</span>
                    <span class="font-medium" style="color: ${data.color}">${data.count}</span>
                </span>
            `;
        }).join('');

        container.innerHTML = `
            <div class="flex items-center space-x-3">
                ${reactionsHtml}
                <span class="text-gray-500 text-sm">${reactions.total} réaction${reactions.total > 1 ? 's' : ''}</span>
            </div>
        `;
    }

    showFeedback(reactionType, action) {
        const reaction = this.reactionTypes[reactionType];
        let message = '';

        switch (action) {
            case 'added':
                message = `Réaction ${reaction.emoji} ${reaction.label} ajoutée!`;
                break;
            case 'updated':
                message = `Réaction changée en ${reaction.emoji} ${reaction.label}!`;
                break;
            case 'removed':
                message = 'Réaction supprimée!';
                break;
        }

        const toast = this.createToast(message, 'success');
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    showError(message) {
        const toast = this.createToast(message, 'error');
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    createToast(message, type) {
        const toast = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        
        toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300`;
        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                <span>${type === 'success' ? '✅' : '❌'}</span>
                <span>${message}</span>
            </div>
        `;

        return toast;
    }

    // Méthode pour initialiser les réactions sur une page
    static initialize() {
        return new GroupReactionSystem();
    }
}

// Auto-initialisation
if (typeof window !== 'undefined') {
    window.GroupReactionSystem = GroupReactionSystem;
    GroupReactionSystem.initialize();
}