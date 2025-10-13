@extends('frontoffice.layouts.app')
@section('title', $group->name . ' - Bookly')

@section('content')

<!-- Toast Success Message -->
@if(session('success'))
    <div id="toast-success" class="fixed z-50 right-6 bottom-6 bg-green-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 animate-fade-in-up" style="min-width:220px;">
        <span class="text-xl">✅</span>
        <span>{{ session('success') }}</span>
    </div>
    <script>
        setTimeout(function() {
            var toast = document.getElementById('toast-success');
            if (toast) toast.style.display = 'none';
        }, 3500);
    </script>
    <style>
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.5s; }
    </style>
@endif

<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-6">
    <div class="container mx-auto px-4 max-w-6xl flex flex-col md:flex-row gap-8">
        <!-- Colonne gauche : Infos groupe -->
        <aside class="md:w-1/3 w-full flex flex-col gap-6 order-2 md:order-1">
            <!-- Header du groupe modernisé -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center mb-3 overflow-hidden">
                    @if($group->image)
                        <img src="{{ asset('storage/'.$group->image) }}" alt="Image du groupe" class="w-full h-full object-cover rounded-full">
                    @else
                        <span class="text-white text-3xl">👥</span>
                    @endif
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $group->name }}</h1>
                <div class="flex flex-wrap gap-2 justify-center mb-2">
                    <span class="bg-primary py-1 px-3 text-xs text-white rounded-lg">{{ $group->theme }}</span>
                    <span class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs px-3 py-1 rounded-full">{{ $group->is_public ? 'Public' : 'Privé' }}</span>
                </div>
                <p class="text-gray-700 dark:text-gray-300 text-sm mb-2">{{ $group->description }}</p>
                <div class="flex flex-col gap-1 text-xs text-gray-500 dark:text-gray-400 items-center">
                    <span>👤 Créé par {{ $group->creator->name ?? 'Administrateur' }}</span>
                    <span>📅 Créé le {{ $group->created_at->format('d/m/Y') }}</span>
                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-3 py-1 rounded-full font-bold mt-2">{{ $memberCount }} membres</span>
                    <span class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 text-xs px-3 py-1 rounded-full font-bold">{{ $posts->count() }} publications</span>
                </div>
            </div>
            <!-- Membres récents -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-4">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-3">Membres récents</h3>
                <div class="grid grid-cols-4 gap-2 mb-3">
                    @foreach($recentMembers as $member)
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-gray-300 to-gray-400 rounded-full mx-auto mb-1 flex items-center justify-center text-white text-sm font-bold">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            <span class="text-xs text-gray-600 dark:text-gray-400 truncate block">{{ explode(' ', $member->name)[0] }}</span>
                        </div>
                    @endforeach
                </div>
                <button class="w-full text-center text-blue-600 dark:text-blue-400 text-sm font-medium py-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    Voir tous les membres
                </button>
            </div>
        </aside>
        <!-- Colonne centrale : Posts -->
        <main class="md:w-2/3 w-full flex flex-col gap-6 order-1 md:order-2">
            <!-- Formulaire de publication façon Facebook -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 flex items-start gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <form action="{{ route('frontoffice.group.wall.post', $group->id) }}" method="POST" class="flex-1 flex items-center gap-2">
                    @csrf
                    <textarea name="content" rows="2" class="w-full border border-gray-200 dark:border-gray-600 rounded-full px-5 py-3 text-base resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white placeholder-gray-500" placeholder="Exprimez-vous dans ce groupe..."></textarea>
                    <button type="submit" class="bg-purple-700 hover:bg-purple-800 text-black px-6 py-2 rounded-full text-base font-bold shadow-lg flex items-center gap-2 transition-all duration-150 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-700 disabled:opacity-50">
            @if(session('success'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 border border-green-300">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-800 border border-red-300">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                        <span class="text-lg">✉️</span> Poster
                    </button>
                </form>
            </div>
            <!-- Liste des publications -->
            @forelse($posts as $post)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow mb-6 hover:shadow-xl transition-all duration-200 overflow-hidden">
                    <div class="p-5 pb-3 flex items-start gap-4">
                        <!-- Avatar principal du post -->
                        <div class="w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($post->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <!-- Ajout de l'icône alphabet à côté du nom -->
                                <span class="w-7 h-7 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 rounded-full flex items-center justify-center font-bold text-base">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $post->user->name }}</span>
                                <span class="text-xs text-gray-400">• {{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="text-gray-800 dark:text-gray-200 leading-relaxed mb-2">{{ $post->content }}</div>
                            <div class="flex gap-4 text-sm text-gray-500 dark:text-gray-400 mb-2">
                                <button class="flex items-center gap-1 hover:text-blue-600 transition-all"><span>👍</span> J'aime</button>
                                <button class="flex items-center gap-1 hover:text-blue-600 transition-all"><span>💬</span> Commenter</button>
                                <button class="flex items-center gap-1 hover:text-blue-600 transition-all"><span>🔗</span> Partager</button>
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors text-xl px-2">⋯</button>
                    </div>
                    <!-- Commentaires -->
                    <div class="bg-gray-50 dark:bg-gray-750 px-6 py-4">
                        @forelse($post->comments as $comment)
                            <div class="flex gap-3 mb-4 last:mb-0 items-start">
                                <!-- Avatar du commentaire -->
                                <div class="w-9 h-9 bg-gradient-to-br from-gray-300 to-gray-500 rounded-full flex items-center justify-center text-white text-base font-bold flex-shrink-0">
                                    {{ substr($comment->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <div class="bg-white dark:bg-gray-700 rounded-2xl px-4 py-2 mb-1 shadow-sm">
                                        <div class="flex items-center gap-2 mb-1">
                                            <!-- Ajout de l'icône alphabet à côté du nom du commentateur -->
                                            <span class="w-6 h-6 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 rounded-full flex items-center justify-center font-bold text-xs">
                                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                            </span>
                                            <span class="font-semibold text-sm text-gray-900 dark:text-white">{{ $comment->user->name }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-800 dark:text-gray-200 mb-0">{{ $comment->content }}</p>
                                        @if($comment->file)
                                            <div class="mt-2">
                                                @php $ext = strtolower(pathinfo($comment->file, PATHINFO_EXTENSION)); @endphp
                                                @if(in_array($ext, ['jpg','jpeg','png','gif']))
                                                    <img src="{{ asset('storage/' . $comment->file) }}" alt="Image" class="max-w-full rounded-lg shadow border max-h-40">
                                                @elseif($ext === 'pdf')
                                                    <a href="{{ asset('storage/' . $comment->file) }}" target="_blank" class="inline-flex items-center space-x-1 text-blue-600 dark:text-blue-400 text-sm">
                                                        <span>📄</span>
                                                        <span>Document PDF</span>
                                                    </a>
                                                @else
                                                    <a href="{{ asset('storage/' . $comment->file) }}" target="_blank" class="inline-flex items-center space-x-1 text-blue-600 dark:text-blue-400 text-sm">
                                                        <span>📎</span>
                                                        <span>Télécharger le fichier</span>
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3 mt-1 px-1">
                                        <button class="text-xs text-gray-500 dark:text-gray-400 hover:text-blue-500 transition-all">J'aime</button>
                                        <button class="text-xs text-gray-500 dark:text-gray-400 hover:text-blue-500 transition-all">Répondre</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 dark:text-gray-400 text-sm py-2">
                                Aucun commentaire pour le moment
                            </div>
                        @endforelse
                        <!-- Formulaire de commentaire -->
                        <form action="{{ route('frontoffice.group.comment', [$group->id, $post->id]) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3 mt-2">
                            @csrf
                            <div class="w-9 h-9 bg-gradient-to-br from-gray-300 to-gray-500 rounded-full flex items-center justify-center text-white text-base font-bold flex-shrink-0">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="flex-1 flex items-center gap-2 bg-gray-100 dark:bg-gray-700 rounded-full px-3 py-2">
                                <textarea name="content" rows="1" class="w-full border-0 bg-transparent focus:ring-0 text-sm resize-none dark:text-white placeholder-gray-500" placeholder="Commenter ce post..."></textarea>
                                <button type="submit" class="ml-2 bg-purple-700 hover:bg-purple-800 text-black px-4 py-2 rounded-full text-xs font-bold flex items-center gap-1 shadow transition-colors">
                                    <span class="text-base">➡️</span> Commenter
                                </button>
                            </div>
                            <button type="button" class="text-gray-400 hover:text-blue-500 transition-colors" title="Emoji">
                                <span class="text-sm">😊</span>
                            </button>
                            <label for="file-{{ $post->id }}" class="cursor-pointer text-gray-400 hover:text-green-500 transition-colors" title="Joindre un fichier">
                                <span class="text-sm">📎</span>
                            </label>
                            <input type="file" name="file" id="file-{{ $post->id }}" class="hidden" accept="image/*,application/pdf">
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center">
                    <div class="text-6xl mb-4 text-gray-300 dark:text-gray-600">📝</div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Aucune publication</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Soyez le premier à publier dans ce groupe !</p>
                </div>
            @endforelse
        </main>
    </div>
</div>
@endsection

@push('styles')
<style>
.dark .bg-gray-750 {
    background-color: #2d3748;
}

/* Animation pour les nouveaux posts */
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

.bg-white, .bg-gray-50 {
    animation: fadeInUp 0.5s ease-out;
}

/* Style pour la zone de texte qui s'agrandit */
textarea {
    transition: all 0.3s ease;
}

textarea:focus {
    border-radius: 1rem !important;
}

/* Style pour les avatars */
.w-10.h-10, .w-8.h-8 {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Effets d'ombre modernes */
.shadow-sm {
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.rounded-xl {
    border-radius: 0.75rem;
}

/* Transition fluide */
.transition-colors {
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
}

/* Responsive design amélioré */
@media (max-width: 1024px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .flex-col.lg\:flex-row {
        flex-direction: column;
    }
    
    .lg\:w-1\/3, .lg\:w-2\/3 {
        width: 100%;
    }
}

/* Scrollbar personnalisée */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.dark ::-webkit-scrollbar-track {
    background: #374151;
}

.dark ::-webkit-scrollbar-thumb {
    background: #6b7280;
}

.dark ::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}
</style>
@endpush

@push('scripts')
<script>
// Animation pour le textarea de publication et gestion dynamique du bouton Poster
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('textarea[name="content"]');
    const submitBtn = document.querySelector('form[action*="wall.post"] button[type="submit"]');
    if (textarea && submitBtn) {
        // Initialiser l'état du bouton
        submitBtn.disabled = textarea.value.trim().length === 0;
        textarea.addEventListener('input', function() {
            // Ajuster la hauteur automatiquement
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
            // Activer/désactiver le bouton
            submitBtn.disabled = this.value.trim().length === 0;
        });
    }
    // Animation pour les nouveaux posts
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        node.style.animation = 'fadeInUp 0.5s ease-out';
                    }
                });
            }
        });
    });
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});
</script>
@endpush