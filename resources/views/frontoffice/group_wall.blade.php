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
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6 flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center mb-3 overflow-hidden shadow-md">
                    @if($group->image)
                        <img src="{{ asset('storage/'.$group->image) }}" alt="Image du groupe" class="w-full h-full object-cover rounded-full">
                    @else
                        <span class="text-white text-3xl">👥</span>
                    @endif
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $group->name }}</h1>
                <div class="flex flex-wrap gap-2 justify-center mb-2">
                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-3 py-1 rounded-full font-medium">{{ $group->theme }}</span>
                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs px-3 py-1 rounded-full">{{ $group->is_public ? 'Public' : 'Privé' }}</span>
                </div>
                <p class="text-gray-700 dark:text-gray-300 text-sm mb-4 leading-relaxed">{{ $group->description }}</p>
                <div class="flex flex-col gap-2 text-sm text-gray-600 dark:text-gray-400 items-center w-full">
                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full font-bold text-xs">A</span>
                        <span>Créé par {{ $group->creator->name ?? 'Administrateur' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="bg-gray-100 dark:bg-gray-700 p-1 rounded-full">📅</span>
                        <span>Créé le {{ $group->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-center gap-3 mt-2 w-full">
                        <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs px-3 py-1 rounded-full font-bold">{{ $memberCount }} membres</span>
                        <span class="bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-xs px-3 py-1 rounded-full font-bold">{{ $posts->count() }} publications</span>
                    </div>
                </div>
            </div>
            
            <!-- Membres récents -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 p-1 rounded-full">👥</span>
                    Membres récents
                </h3>
                <div class="grid grid-cols-4 gap-3 mb-3">
                    @foreach($recentMembers as $member)
                        <div class="text-center group cursor-pointer">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full mx-auto mb-1 flex items-center justify-center text-white text-sm font-bold shadow-md group-hover:shadow-lg transition-all">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            <div class="flex items-center justify-center gap-1">
                                <span class="flex items-center justify-center w-5 h-5 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full font-bold text-xs">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                                <span class="text-xs text-gray-600 dark:text-gray-400 truncate block group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ explode(' ', $member->name)[0] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="w-full text-center text-blue-600 dark:text-blue-400 text-sm font-medium py-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors flex items-center justify-center gap-1">
                    Voir tous les membres
                    <span>→</span>
                </button>
            </div>
            
            <!-- Règles du groupe -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <span class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 p-1 rounded-full">📋</span>
                    Règles du groupe
                </h3>
                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                    <li class="flex items-start gap-2">
                        <span class="text-green-500 mt-0.5">•</span>
                        <span>Soyez respectueux des autres membres</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-500 mt-0.5">•</span>
                        <span>Partagez du contenu pertinent</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-500 mt-0.5">•</span>
                        <span>Pas de spam ou de publicité non autorisée</span>
                    </li>
                </ul>
            </div>
        </aside>
        
        <!-- Colonne centrale : Posts -->
        <main class="md:w-2/3 w-full flex flex-col gap-6 order-1 md:order-2">
            <!-- Formulaire de publication façon Facebook -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                        <form action="{{ route('frontoffice.group.wall.post', $group->id) }}" method="POST" enctype="multipart/form-data" class="flex-1">
                        @csrf
                        <textarea name="content" rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 text-base resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white placeholder-gray-500 transition-all" placeholder="Exprimez-vous dans ce groupe..."></textarea>
                        <div class="flex justify-between items-center mt-3 px-1">
                            <div class="flex gap-2 items-center">
                                <label for="post-file" class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors text-sm p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                    <span class="text-lg">🖼️</span>
                                    <span>Photo</span>
                                </label>
                                <label for="post-file" class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors text-sm p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                    <span class="text-lg">📹</span>
                                    <span>Vidéo</span>
                                </label>
                                <label for="post-file" class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors text-sm p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                    <span class="text-lg">📄</span>
                                    <span>PDF</span>
                                </label>
                                <input type="file" name="file" id="post-file" class="hidden" accept="image/*,video/*,application/pdf">
                            </div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-base font-medium shadow-md flex items-center gap-2 transition-all duration-150 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="text-lg">📤</span>
                                Publier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Filtres de publications -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-3">
                <div class="flex items-center gap-2 overflow-x-auto pb-1">
                    <button class="bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 px-3 py-1.5 rounded-full text-sm font-medium whitespace-nowrap">Tous les posts</button>
                    <button class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-full text-sm font-medium whitespace-nowrap hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Articles</button>
                    <button class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-full text-sm font-medium whitespace-nowrap hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Photos</button>
                    <button class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-full text-sm font-medium whitespace-nowrap hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Vidéos</button>
                    <button class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-full text-sm font-medium whitespace-nowrap hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Fichiers</button>
                </div>
            </div>
            
            <!-- Liste des publications -->
            @forelse($posts as $post)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-200 hover:shadow-lg">
                    <!-- En-tête du post -->
                    <div class="p-4 flex items-start gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                            {{ substr($post->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="flex items-center justify-center w-6 h-6 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full font-bold text-xs">{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $post->user->name }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">•</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="text-gray-800 dark:text-gray-200 leading-relaxed mb-2 whitespace-pre-wrap">{{ $post->content }}</div>
                            @if($post->file)
                                <div class="mt-2">
                                    @php $ext = strtolower(pathinfo($post->file, PATHINFO_EXTENSION)); @endphp
                                    @if(in_array($ext, ['jpg','jpeg','png','gif']))
                                        <img src="{{ asset('storage/' . $post->file) }}" alt="Image" class="max-w-full rounded-lg shadow border max-h-80">
                                    @elseif(in_array($ext, ['mp4','mov','avi']))
                                        <video controls class="max-w-full rounded-lg shadow border max-h-80">
                                            <source src="{{ asset('storage/' . $post->file) }}" type="video/{{ $ext }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @elseif($ext === 'pdf')
                                        <a href="{{ asset('storage/' . $post->file) }}" target="_blank" class="inline-flex items-center space-x-1 text-blue-600 dark:text-blue-400 text-sm">
                                            <span>📄</span>
                                            <span>Document PDF</span>
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $post->file) }}" target="_blank" class="inline-flex items-center space-x-1 text-blue-600 dark:text-blue-400 text-sm">
                                            <span>📎</span>
                                            <span>Télécharger le fichier</span>
                                        </a>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Actions du post -->
                            <div class="flex justify-between items-center pt-2 border-t border-gray-100 dark:border-gray-700 mt-3">
                                <button class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors px-2 py-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <span class="text-lg">👍</span>
                                    <span class="text-sm font-medium">J'aime</span>
                                </button>
                                <button class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors px-2 py-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 comment-trigger" data-post-id="{{ $post->id }}">
                                    <span class="text-lg">💬</span>
                                    <span class="text-sm font-medium">Commenter</span>
                                </button>
                                <button class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors px-2 py-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <span class="text-lg">🔗</span>
                                    <span class="text-sm font-medium">Partager</span>
                                </button>
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors text-xl p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" title="Plus d'options">
                            ⋯
                        </button>
                    </div>
                    
                    <!-- Commentaires existants -->
                    @if($post->comments->count() > 0)
                        <div class="bg-gray-50 dark:bg-gray-750 px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                            <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2 flex items-center gap-1">
                                <span>💬</span>
                                {{ $post->comments->count() }} commentaire(s)
                            </div>
                            
                            @foreach($post->comments as $comment)
                                <div class="flex gap-3 mb-4 last:mb-0 items-start">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-black text-sm font-bold flex-shrink-0 shadow-sm">
                                        {{ substr($comment->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="bg-white dark:bg-gray-700 rounded-xl px-3 py-2 mb-1 shadow-sm border border-gray-100 dark:border-gray-600">
                                            <div class="flex items-center gap-2 mb-1">
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
                                            <button class="text-xs text-gray-500 dark:text-gray-400 hover:text-blue-500 transition-colors px-1 py-0.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700">J'aime</button>
                                            <button class="text-xs text-gray-500 dark:text-gray-400 hover:text-blue-500 transition-colors px-1 py-0.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Répondre</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <!-- Formulaire de commentaire -->
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-750 border-t border-gray-100 dark:border-gray-700">
                        <form action="{{ route('frontoffice.group.comment', [$group->id, $post->id]) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                            @csrf
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-black text-sm font-bold flex-shrink-0 shadow-sm">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="flex-1 flex items-center bg-white dark:bg-gray-700 rounded-full px-3 py-1.5 border border-gray-200 dark:border-gray-600 shadow-sm">
                                <textarea name="content" rows="1" class="w-full border-0 bg-transparent focus:ring-0 text-sm resize-none dark:text-white placeholder-gray-500 focus:outline-none comment-field" data-post-id="{{ $post->id }}" placeholder="Écrire un commentaire..."></textarea>
                                <div class="flex items-center gap-1 ml-2">
                                    <button type="button" class="text-gray-400 hover:text-yellow-500 transition-colors p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600" title="Emoji">
                                        <span class="text-sm">😊</span>
                                    </button>
                                    <label for="file-{{ $post->id }}" class="cursor-pointer text-gray-400 hover:text-green-500 transition-colors p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600" title="Joindre un fichier">
                                        <span class="text-sm">📎</span>
                                    </label>
                                    <input type="file" name="file" id="file-{{ $post->id }}" class="hidden" accept="image/*,application/pdf">
                                    <button type="submit" name="send_file" class="ml-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1 shadow transition-colors" style="min-width:90px;">
                                        <span class="text-base">📤</span> Envoyer fichier
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full text-sm font-medium shadow-sm transition-all duration-150 hover:shadow-md ml-1 comment-submit" data-post-id="{{ $post->id }}" disabled>
                                <span class="text-base">➡️</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-8 text-center">
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
    min-height: 40px;
    max-height: 120px;
}

textarea:focus {
    border-radius: 0.75rem !important;
}

/* Style pour les avatars avec effet de profondeur */
.w-10.h-10, .w-8.h-8 {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Effets d'ombre modernes */
.shadow-md {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Transition fluide */
.transition-colors {
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
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

/* Style pour les boutons d'action des posts */
button:hover {
    transform: translateY(-1px);
}

/* Style pour les champs de commentaire avec placeholder */
.comment-field::placeholder {
    color: #9ca3af;
}

.dark .comment-field::placeholder {
    color: #6b7280;
}

/* Style pour les icônes alphabet */
.w-6.h-6, .w-5.h-5 {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du textarea de publication principale
    const mainTextarea = document.querySelector('textarea[name="content"]');
    const mainSubmitBtn = document.querySelector('form[action*="wall.post"] button[type="submit"]');
    
    if (mainTextarea && mainSubmitBtn) {
        mainSubmitBtn.disabled = mainTextarea.value.trim().length === 0;
        
        mainTextarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
            mainSubmitBtn.disabled = this.value.trim().length === 0;
        });
    }
    
    // Gestion des champs de commentaire
    const commentFields = document.querySelectorAll('.comment-field');
    const commentSubmits = document.querySelectorAll('.comment-submit');
    const commentTriggers = document.querySelectorAll('.comment-trigger');
    
    commentFields.forEach(field => {
        const postId = field.dataset.postId;
        const submitBtn = document.querySelector(`.comment-submit[data-post-id="${postId}"]`);
        
        if (submitBtn) {
            submitBtn.disabled = field.value.trim().length === 0;
            
            field.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
                submitBtn.disabled = this.value.trim().length === 0;
            });
        }
    });
    
    // Focus sur le champ de commentaire quand on clique sur "Commenter"
    commentTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const commentField = document.querySelector(`.comment-field[data-post-id="${postId}"]`);
            if (commentField) {
                commentField.focus();
            }
        });
    });
    
    // Animation pour les nouveaux posts
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) {
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