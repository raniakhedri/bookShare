/**
 * AudioBookReader - Système de lecture audio avancé pour les PDFs
 * Fonctionnalités: Text-to-Speech, contrôles audio, extraction PDF, sauvegarde de progression
 */

class AdvancedAudioBookReader {
    constructor(options = {}) {
        // Configuration par défaut
        this.config = {
            pdfUrl: options.pdfUrl || null,
            bookId: options.bookId || null,
            bookTitle: options.bookTitle || 'Unknown Book',
            enablePdfExtraction: options.enablePdfExtraction || false,
            autoSave: options.autoSave !== false,
            chunkSize: options.chunkSize || 200, // mots par chunk
            ...options
        };
        
        // État du lecteur
        this.synthesis = window.speechSynthesis;
        this.utterance = null;
        this.isReading = false;
        this.isPaused = false;
        this.currentText = '';
        this.textChunks = [];
        this.currentChunkIndex = 0;
        this.currentPosition = 0;
        this.totalCharacters = 0;
        this.voices = [];
        this.selectedVoice = null;
        this.readingSpeed = 1.0;
        this.volume = 1.0;
        
        // Éléments DOM
        this.elements = {};
        
        // Callbacks
        this.onProgress = options.onProgress || null;
        this.onStart = options.onStart || null;
        this.onPause = options.onPause || null;
        this.onStop = options.onStop || null;
        this.onEnd = options.onEnd || null;
        this.onError = options.onError || null;
        
        // Initialisation
        this.init();
    }
    
    async init() {
        try {
            this.bindElements();
            await this.loadVoices();
            this.bindEvents();
            this.loadSettings();
            
            if (this.config.enablePdfExtraction && this.config.pdfUrl) {
                await this.loadPdfText();
            } else {
                this.setDemoText();
            }
            
            this.loadReadingPosition();
            console.log('AdvancedAudioBookReader initialized successfully');
            
        } catch (error) {
            console.error('Erreur lors de l\'initialisation:', error);
            this.handleError('Erreur lors de l\'initialisation du lecteur audio');
        }
    }
    
    bindElements() {
        this.elements = {
            playPauseBtn: document.getElementById('playPauseBtn'),
            stopBtn: document.getElementById('stopBtn'),
            speedControl: document.getElementById('speedControl'),
            voiceSelect: document.getElementById('voiceSelect'),
            volumeControl: document.getElementById('volumeControl'),
            progressBar: document.getElementById('progressBar'),
            progressText: document.getElementById('progressText'),
            audioProgress: document.getElementById('audioProgress'),
            playIcon: document.getElementById('playIcon'),
            pauseIcon: document.getElementById('pauseIcon'),
            currentChapter: document.getElementById('currentChapter'),
            timeRemaining: document.getElementById('timeRemaining')
        };
    }
    
    async loadVoices() {
        return new Promise((resolve) => {
            const loadVoicesCallback = () => {
                this.voices = this.synthesis.getVoices().filter(voice => 
                    voice.lang.includes('en') || 
                    voice.lang.includes('fr') ||
                    voice.lang.includes('es') ||
                    voice.name.includes('Google') ||
                    voice.name.includes('Microsoft') ||
                    voice.name.includes('Amazon')
                );
                
                this.populateVoiceSelect();
                resolve();
            };
            
            if (this.synthesis.getVoices().length > 0) {
                loadVoicesCallback();
            } else {
                this.synthesis.addEventListener('voiceschanged', loadVoicesCallback);
                setTimeout(loadVoicesCallback, 2000);
            }
        });
    }
    
    populateVoiceSelect() {
        if (!this.elements.voiceSelect) return;
        
        // Vider les options existantes
        this.elements.voiceSelect.innerHTML = '<option value="">Voix par défaut</option>';
        
        // Grouper les voix par langue
        const voiceGroups = {};
        this.voices.forEach((voice, index) => {
            const lang = voice.lang.split('-')[0];
            if (!voiceGroups[lang]) voiceGroups[lang] = [];
            voiceGroups[lang].push({ voice, index });
        });
        
        // Créer les options groupées
        Object.keys(voiceGroups).sort().forEach(lang => {
            const optgroup = document.createElement('optgroup');
            optgroup.label = this.getLanguageName(lang);
            
            voiceGroups[lang]
                .sort((a, b) => {
                    // Prioriser les voix premium
                    const aPremium = this.isPremiumVoice(a.voice);
                    const bPremium = this.isPremiumVoice(b.voice);
                    if (aPremium !== bPremium) return bPremium - aPremium;
                    return a.voice.name.localeCompare(b.voice.name);
                })
                .forEach(({ voice, index }) => {
                    const option = document.createElement('option');
                    option.value = index;
                    option.textContent = this.formatVoiceName(voice);
                    if (voice.default) option.selected = true;
                    optgroup.appendChild(option);
                });
            
            this.elements.voiceSelect.appendChild(optgroup);
        });
    }
    
    isPremiumVoice(voice) {
        return voice.name.includes('Google') || 
               voice.name.includes('Microsoft') || 
               voice.name.includes('Amazon') ||
               voice.name.includes('Neural');
    }
    
    formatVoiceName(voice) {
        let name = voice.name;
        if (this.isPremiumVoice(voice)) {
            name = `⭐ ${name}`;
        }
        return `${name} (${voice.lang})`;
    }
    
    getLanguageName(code) {
        const languages = {
            'en': 'English',
            'fr': 'Français', 
            'es': 'Español',
            'de': 'Deutsch',
            'it': 'Italiano',
            'pt': 'Português'
        };
        return languages[code] || code.toUpperCase();
    }
    
    async loadPdfText() {
        if (!this.config.pdfUrl) {
            throw new Error('URL du PDF non fournie');
        }
        
        try {
            this.showNotification('Extraction du texte PDF en cours...', 'info');
            
            // Utiliser PDF.js pour extraire le texte réel
            if (typeof pdfjsLib !== 'undefined') {
                const extractedText = await this.extractTextWithPdfJs();
                if (extractedText && extractedText.length > 50) {
                    this.currentText = extractedText;
                    this.prepareTextForReading();
                    this.showNotification('Texte PDF extrait avec succès !', 'success');
                    return;
                }
            }
            
            // Fallback vers l'extraction côté serveur
            const serverText = await this.extractTextFromServer();
            if (serverText && serverText.length > 50) {
                this.currentText = serverText;
                this.prepareTextForReading();
                this.showNotification('Texte extrait côté serveur', 'success');
                return;
            }
            
            // Dernier recours : texte de démonstration
            this.setDemoText();
            this.showNotification('Utilisation du texte de démonstration', 'warning');
            
        } catch (error) {
            console.error('Erreur extraction PDF:', error);
            this.setDemoText();
            this.showNotification('Erreur d\'extraction - mode démonstration', 'warning');
        }
    }

    async extractTextWithPdfJs() {
        try {
            console.log('Tentative d\'extraction PDF avec PDF.js depuis:', this.config.pdfUrl);
            
            const loadingTask = pdfjsLib.getDocument(this.config.pdfUrl);
            const pdf = await loadingTask.promise;
            
            let fullText = '';
            const maxPages = Math.min(pdf.numPages, 10); // Limiter à 10 pages pour la démo
            
            for (let pageNum = 1; pageNum <= maxPages; pageNum++) {
                try {
                    const page = await pdf.getPage(pageNum);
                    const textContent = await page.getTextContent();
                    
                    let pageText = '';
                    textContent.items.forEach(item => {
                        if (item.str && item.str.trim()) {
                            pageText += item.str + ' ';
                        }
                    });
                    
                    if (pageText.trim()) {
                        fullText += pageText.trim() + '\n\n';
                    }
                    
                    // Mettre à jour le progrès
                    const progress = (pageNum / maxPages) * 100;
                    this.showNotification(`Extraction page ${pageNum}/${maxPages} (${Math.round(progress)}%)`, 'info');
                    
                } catch (pageError) {
                    console.warn(`Erreur page ${pageNum}:`, pageError);
                }
            }
            
            if (fullText.trim().length > 50) {
                console.log(`Texte extrait avec succès: ${fullText.length} caractères`);
                return this.cleanExtractedText(fullText);
            } else {
                throw new Error('Texte extrait trop court ou vide');
            }
            
        } catch (error) {
            console.error('Erreur PDF.js:', error);
            throw error;
        }
    }

    async extractTextFromServer() {
        try {
            const response = await fetch('/audiobook/books/' + this.config.bookId + '/extract-text');
            const data = await response.json();
            
            if (data.success && data.extraction && data.extraction.text) {
                return data.extraction.text;
            }
            
            throw new Error('Extraction serveur échouée');
        } catch (error) {
            console.error('Erreur extraction serveur:', error);
            return null;
        }
    }

    cleanExtractedText(text) {
        // Nettoyer le texte extrait
        return text
            // Supprimer les caractères de contrôle
            .replace(/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/g, '')
            // Normaliser les espaces
            .replace(/[ \t]+/g, ' ')
            // Normaliser les nouvelles lignes
            .replace(/\n\s*\n\s*\n/g, '\n\n')
            // Supprimer les lignes très courtes (probablement des artefacts)
            .split('\n')
            .filter(line => line.trim().length > 3)
            .join('\n')
            // Nettoyer les points multiples
            .replace(/\.{3,}/g, '...')
            .trim();
    }
    
    setDemoText() {
        this.currentText = this.generateDemoText();
        this.prepareTextForReading();
        
        // Afficher une notification explicative
        setTimeout(() => {
            this.showNotification('📖 Mode démonstration activé - Le texte réel du PDF nécessite une configuration avancée', 'warning');
        }, 2000);
    }
    
    generateDemoText() {
        return `
            Lecture audio de "${this.config.bookTitle}".
            
            Attention : le texte du PDF n'a pas pu être extrait automatiquement. Ceci est un texte de démonstration.
            
            Pour une extraction complète du texte PDF, plusieurs options sont disponibles :
            
            Premièrement, l'extraction automatique avec PDF.js nécessite que le PDF soit accessible et non protégé.
            
            Deuxièmement, certains PDFs sont des images scannées et nécessitent une reconnaissance optique de caractères (OCR).
            
            Troisièmement, les PDFs protégés par mot de passe ou chiffrés ne peuvent pas être traités automatiquement.
            
            Quatrièmement, vous pouvez tester toutes les fonctionnalités audio avec ce texte de démonstration.
            
            Les contrôles incluent : lecture, pause, arrêt, changement de vitesse, sélection de voix, et navigation par chapitres.
            
            Vous pouvez ajuster la vitesse de lecture entre 0.5x et 2x pour votre confort d'écoute.
            
            Le système sauvegarde automatiquement votre position de lecture pour reprendre où vous vous êtes arrêté.
            
            Cette technologie rend les livres plus accessibles et offre une alternative pratique à la lecture traditionnelle.
            
            Profitez de cette expérience audio innovante pour découvrir une nouvelle façon de consommer du contenu littéraire !
        `;
    }
    
    prepareTextForReading() {
        // Nettoyer et préparer le texte
        const cleanText = this.currentText
            .replace(/\s+/g, ' ')
            .replace(/\n\s*\n/g, '\n\n')
            .trim();
        
        // Diviser en phrases
        const sentences = cleanText
            .split(/(?<=[.!?])\s+/)
            .filter(sentence => sentence.trim().length > 0);
        
        // Grouper en chunks plus gros pour une lecture fluide
        this.textChunks = [];
        let currentChunk = '';
        let wordCount = 0;
        
        sentences.forEach(sentence => {
            const words = sentence.split(/\s+/).length;
            
            if (wordCount + words > this.config.chunkSize && currentChunk) {
                this.textChunks.push(currentChunk.trim());
                currentChunk = sentence;
                wordCount = words;
            } else {
                currentChunk += (currentChunk ? ' ' : '') + sentence;
                wordCount += words;
            }
        });
        
        if (currentChunk.trim()) {
            this.textChunks.push(currentChunk.trim());
        }
        
        this.totalCharacters = cleanText.length;
        console.log(`Texte préparé: ${this.textChunks.length} chunks, ${this.totalCharacters} caractères`);
    }
    
    bindEvents() {
        // Bouton play/pause
        if (this.elements.playPauseBtn) {
            this.elements.playPauseBtn.addEventListener('click', () => {
                this.togglePlayPause();
            });
        }
        
        // Bouton stop
        if (this.elements.stopBtn) {
            this.elements.stopBtn.addEventListener('click', () => {
                this.stop();
            });
        }
        
        // Contrôle de vitesse
        if (this.elements.speedControl) {
            this.elements.speedControl.addEventListener('change', (e) => {
                this.setSpeed(parseFloat(e.target.value));
            });
        }
        
        // Sélection de voix
        if (this.elements.voiceSelect) {
            this.elements.voiceSelect.addEventListener('change', (e) => {
                this.setVoice(parseInt(e.target.value));
            });
        }
        
        // Contrôle de volume
        if (this.elements.volumeControl) {
            this.elements.volumeControl.addEventListener('input', (e) => {
                this.setVolume(parseFloat(e.target.value));
            });
        }
        
        // Raccourcis clavier
        this.bindKeyboardShortcuts();
        
        // Sauvegarde automatique
        if (this.config.autoSave) {
            setInterval(() => this.saveSettings(), 30000); // Sauvegarde toutes les 30s
        }
    }
    
    bindKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ignorer si focus dans un input
            if (e.target.tagName.toLowerCase() === 'input' || 
                e.target.tagName.toLowerCase() === 'textarea') {
                return;
            }
            
            switch(e.code) {
                case 'Space':
                    e.preventDefault();
                    this.togglePlayPause();
                    break;
                case 'KeyS':
                    if (e.ctrlKey || e.metaKey) {
                        e.preventDefault();
                        this.stop();
                    }
                    break;
                case 'ArrowRight':
                    if (e.ctrlKey) {
                        e.preventDefault();
                        this.skipForward();
                    }
                    break;
                case 'ArrowLeft':
                    if (e.ctrlKey) {
                        e.preventDefault();
                        this.skipBackward();
                    }
                    break;
                case 'ArrowUp':
                    if (e.ctrlKey) {
                        e.preventDefault();
                        this.increaseSpeed();
                    }
                    break;
                case 'ArrowDown':
                    if (e.ctrlKey) {
                        e.preventDefault();
                        this.decreaseSpeed();
                    }
                    break;
            }
        });
    }
    
    togglePlayPause() {
        if (this.isReading) {
            this.pause();
        } else {
            this.play();
        }
    }
    
    async play() {
        try {
            if (!this.currentText) {
                this.showNotification('Aucun texte à lire', 'warning');
                return;
            }
            
            // Reprendre si en pause
            if (this.isPaused && this.synthesis.paused) {
                this.synthesis.resume();
                this.isReading = true;
                this.isPaused = false;
                this.updateUI();
                this.showNotification('Lecture reprise', 'info');
                return;
            }
            
            // Commencer une nouvelle lecture
            await this.startReading();
            
        } catch (error) {
            this.handleError('Erreur lors du démarrage de la lecture');
        }
    }
    
    async startReading() {
        if (this.currentChunkIndex >= this.textChunks.length) {
            this.showNotification('Fin du livre atteinte', 'info');
            this.reset();
            return;
        }
        
        const textToRead = this.textChunks[this.currentChunkIndex];
        
        this.utterance = new SpeechSynthesisUtterance(textToRead);
        this.configureUtterance();
        this.bindUtteranceEvents();
        
        // Démarrer la lecture
        this.synthesis.speak(this.utterance);
        
        this.isReading = true;
        this.isPaused = false;
        this.updateUI();
        
        if (this.onStart) this.onStart();
    }
    
    configureUtterance() {
        this.utterance.rate = this.readingSpeed;
        this.utterance.volume = this.volume;
        this.utterance.pitch = 1;
        
        if (this.selectedVoice) {
            this.utterance.voice = this.selectedVoice;
        }
    }
    
    bindUtteranceEvents() {
        this.utterance.onstart = () => {
            this.showNotification('Lecture démarrée', 'success');
            this.showAudioProgress();
        };
        
        this.utterance.onend = () => {
            this.currentChunkIndex++;
            
            if (this.currentChunkIndex < this.textChunks.length) {
                // Continuer avec le prochain chunk
                setTimeout(() => this.startReading(), 100);
            } else {
                // Fin de la lecture
                this.isReading = false;
                this.updateUI();
                this.updateProgress(100);
                this.showNotification('Lecture terminée', 'success');
                if (this.onEnd) this.onEnd();
            }
            
            this.saveReadingPosition();
        };
        
        this.utterance.onerror = (event) => {
            this.handleError(`Erreur de synthèse vocale: ${event.error}`);
        };
        
        this.utterance.onboundary = (event) => {
            if (event.name === 'word') {
                this.currentPosition = event.charIndex;
                this.updateProgressFromPosition();
            }
        };
    }
    
    pause() {
        if (this.synthesis.speaking && !this.synthesis.paused) {
            this.synthesis.pause();
            this.isReading = false;
            this.isPaused = true;
            this.updateUI();
            this.saveReadingPosition();
            this.showNotification('Lecture en pause', 'info');
            
            if (this.onPause) this.onPause();
        }
    }
    
    stop() {
        this.synthesis.cancel();
        this.isReading = false;
        this.isPaused = false;
        this.updateUI();
        this.hideAudioProgress();
        this.saveReadingPosition();
        this.showNotification('Lecture arrêtée', 'info');
        
        if (this.onStop) this.onStop();
    }
    
    reset() {
        this.stop();
        this.currentChunkIndex = 0;
        this.currentPosition = 0;
        this.updateProgress(0);
        this.saveReadingPosition();
    }
    
    skipForward() {
        if (this.currentChunkIndex < this.textChunks.length - 1) {
            this.currentChunkIndex++;
            this.currentPosition = 0;
            
            if (this.isReading) {
                this.synthesis.cancel();
                setTimeout(() => this.startReading(), 100);
            }
            
            this.updateProgressFromChunk();
            this.saveReadingPosition();
            this.showNotification('Passage suivant', 'info');
        }
    }
    
    skipBackward() {
        if (this.currentChunkIndex > 0) {
            this.currentChunkIndex--;
            this.currentPosition = 0;
            
            if (this.isReading) {
                this.synthesis.cancel();
                setTimeout(() => this.startReading(), 100);
            }
            
            this.updateProgressFromChunk();
            this.saveReadingPosition();
            this.showNotification('Passage précédent', 'info');
        }
    }
    
    setSpeed(speed) {
        this.readingSpeed = Math.max(0.5, Math.min(2.0, speed));
        
        if (this.utterance) {
            // Redémarrer avec la nouvelle vitesse
            if (this.isReading) {
                this.synthesis.cancel();
                setTimeout(() => this.startReading(), 100);
            }
        }
        
        this.saveSettings();
        this.showNotification(`Vitesse: ${this.readingSpeed}x`, 'info');
    }
    
    increaseSpeed() {
        const newSpeed = Math.min(2.0, this.readingSpeed + 0.25);
        this.setSpeed(newSpeed);
        if (this.elements.speedControl) {
            this.elements.speedControl.value = newSpeed;
        }
    }
    
    decreaseSpeed() {
        const newSpeed = Math.max(0.5, this.readingSpeed - 0.25);
        this.setSpeed(newSpeed);
        if (this.elements.speedControl) {
            this.elements.speedControl.value = newSpeed;
        }
    }
    
    setVoice(voiceIndex) {
        if (voiceIndex >= 0 && voiceIndex < this.voices.length) {
            this.selectedVoice = this.voices[voiceIndex];
            
            // Redémarrer avec la nouvelle voix si nécessaire
            if (this.isReading) {
                this.synthesis.cancel();
                setTimeout(() => this.startReading(), 100);
            }
            
            this.saveSettings();
            this.showNotification(`Voix: ${this.selectedVoice.name}`, 'info');
        }
    }
    
    setVolume(volume) {
        this.volume = Math.max(0, Math.min(1, volume));
        
        if (this.utterance) {
            this.utterance.volume = this.volume;
        }
        
        this.saveSettings();
    }
    
    updateUI() {
        // Mettre à jour les boutons
        if (this.elements.playIcon && this.elements.pauseIcon) {
            if (this.isReading) {
                this.elements.playIcon.classList.add('hidden');
                this.elements.pauseIcon.classList.remove('hidden');
                this.elements.playPauseBtn.classList.add('reading-active');
            } else {
                this.elements.playIcon.classList.remove('hidden');
                this.elements.pauseIcon.classList.add('hidden');
                this.elements.playPauseBtn.classList.remove('reading-active');
            }
        }
        
        // Mettre à jour le chapitre actuel
        if (this.elements.currentChapter) {
            this.elements.currentChapter.textContent = `Passage ${this.currentChunkIndex + 1} / ${this.textChunks.length}`;
        }
    }
    
    showAudioProgress() {
        if (this.elements.audioProgress) {
            this.elements.audioProgress.classList.remove('hidden');
        }
    }
    
    hideAudioProgress() {
        if (this.elements.audioProgress) {
            this.elements.audioProgress.classList.add('hidden');
        }
    }
    
    updateProgress(percentage) {
        if (this.elements.progressBar) {
            this.elements.progressBar.style.width = `${percentage}%`;
        }
        
        if (this.elements.progressText) {
            this.elements.progressText.textContent = `${Math.round(percentage)}%`;
        }
        
        if (this.onProgress) {
            this.onProgress(percentage);
        }
    }
    
    updateProgressFromChunk() {
        const progress = (this.currentChunkIndex / this.textChunks.length) * 100;
        this.updateProgress(progress);
    }
    
    updateProgressFromPosition() {
        let totalProcessedChars = 0;
        
        // Compter les caractères des chunks précédents
        for (let i = 0; i < this.currentChunkIndex; i++) {
            totalProcessedChars += this.textChunks[i].length;
        }
        
        // Ajouter la position dans le chunk actuel
        totalProcessedChars += this.currentPosition;
        
        const progress = (totalProcessedChars / this.totalCharacters) * 100;
        this.updateProgress(progress);
    }
    
    saveReadingPosition() {
        if (!this.config.bookId) return;
        
        const position = {
            chunkIndex: this.currentChunkIndex,
            position: this.currentPosition,
            timestamp: Date.now(),
            totalChunks: this.textChunks.length
        };
        
        localStorage.setItem(`audiobook_position_${this.config.bookId}`, JSON.stringify(position));
    }
    
    loadReadingPosition() {
        if (!this.config.bookId) return;
        
        const saved = localStorage.getItem(`audiobook_position_${this.config.bookId}`);
        
        if (saved) {
            try {
                const position = JSON.parse(saved);
                
                if (position.totalChunks === this.textChunks.length) {
                    this.currentChunkIndex = position.chunkIndex || 0;
                    this.currentPosition = position.position || 0;
                    this.updateProgressFromChunk();
                    
                    if (this.currentChunkIndex > 0) {
                        this.showNotification(`Position restaurée: passage ${this.currentChunkIndex + 1}`, 'info');
                    }
                }
            } catch (error) {
                console.error('Erreur lors de la restauration de la position:', error);
            }
        }
    }
    
    saveSettings() {
        const settings = {
            speed: this.readingSpeed,
            voiceIndex: this.voices.findIndex(v => v === this.selectedVoice),
            volume: this.volume
        };
        
        localStorage.setItem('audiobook_settings', JSON.stringify(settings));
    }
    
    loadSettings() {
        const saved = localStorage.getItem('audiobook_settings');
        
        if (saved) {
            try {
                const settings = JSON.parse(saved);
                
                if (settings.speed) {
                    this.readingSpeed = settings.speed;
                    if (this.elements.speedControl) {
                        this.elements.speedControl.value = this.readingSpeed;
                    }
                }
                
                if (settings.voiceIndex >= 0 && settings.voiceIndex < this.voices.length) {
                    this.selectedVoice = this.voices[settings.voiceIndex];
                    if (this.elements.voiceSelect) {
                        this.elements.voiceSelect.value = settings.voiceIndex;
                    }
                }
                
                if (settings.volume) {
                    this.volume = settings.volume;
                    if (this.elements.volumeControl) {
                        this.elements.volumeControl.value = this.volume;
                    }
                }
            } catch (error) {
                console.error('Erreur lors du chargement des paramètres:', error);
            }
        }
    }
    
    handleError(message) {
        console.error(message);
        this.isReading = false;
        this.updateUI();
        this.showNotification(message, 'error');
        
        if (this.onError) {
            this.onError(message);
        }
    }
    
    showNotification(message, type = 'info') {
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-orange-500',
            info: 'bg-blue-500'
        };
        
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full opacity-0 transition-all duration-300 audio-notification max-w-sm`;
        notification.innerHTML = `
            <div class="flex items-center">
                <span class="flex-1">${message}</span>
                <button class="ml-3 text-white/80 hover:text-white" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full', 'opacity-0');
        }, 100);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 5000);
    }
    
    // API publique
    getState() {
        return {
            isReading: this.isReading,
            isPaused: this.isPaused,
            currentChunk: this.currentChunkIndex,
            totalChunks: this.textChunks.length,
            progress: (this.currentChunkIndex / this.textChunks.length) * 100,
            speed: this.readingSpeed,
            volume: this.volume,
            voice: this.selectedVoice?.name || 'Default'
        };
    }
    
    destroy() {
        this.stop();
        this.synthesis.removeEventListener('voiceschanged', this.loadVoices);
        
        // Nettoyer les event listeners
        Object.values(this.elements).forEach(element => {
            if (element && element.removeEventListener) {
                element.removeEventListener('click', () => {});
                element.removeEventListener('change', () => {});
            }
        });
    }
}

// Export pour utilisation globale
if (typeof window !== 'undefined') {
    window.AdvancedAudioBookReader = AdvancedAudioBookReader;
}