@extends('frontoffice.layouts.app')


@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ $book->title }}</h2>
        <a href="{{ route('journals.show', $journal) }}" class="btn btn-secondary">← Retour au journal</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Auteur : {{ $book->author ?? 'Inconnu' }}</h5>

            <!-- Conteneur de texte sélectionnable -->
            <div id="selectable-text" class="selectable-text mb-3" style="line-height: 1.6; cursor: text; font-size: 1.1rem;">
                @php
                    $content = e($book->description);
                    // On va entourer les extraits annotés avec un style de surlignage
                    foreach ($notes as $note) {
                        $safeExcerpt = preg_quote($note->excerpt, '/');
                        $highlighted = '<mark class="highlighted-excerpt bg-warning bg-opacity-50 px-1 rounded" style="background-color: #fff3cd; padding: 0.1rem 0.3rem; border-radius: 0.2rem;">' . e($note->excerpt) . '</mark>';
                        $content = preg_replace('/' . $safeExcerpt . '/', $highlighted, $content, 1); // seulement la 1ère occurrence
                    }
                @endphp
                {!! nl2br($content) !!}
            </div>

            <!-- Bouton flottant pour ajouter une note (caché par défaut) -->
            <div id="note-button" class="d-none mb-3">
                <button class="btn btn-warning btn-sm" onclick="openNoteForm()">
                    📝 Ajouter une note sur ce passage
                </button>
            </div>

            <!-- Formulaire de note (caché par défaut) -->
            <div id="note-form" class="d-none mb-4 p-3 border rounded bg-light">
                <form action="{{ route('notes.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="journal_id" value="{{ $journal->id }}">
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <input type="hidden" name="excerpt" id="selected-excerpt" required>

                    <div class="mb-2">
                        <label class="form-label">Passage surligné :</label>
                        <div class="alert alert-warning" id="excerpt-preview"></div>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Ta note / commentaire</label>
                        <textarea name="content" id="content" class="form-control" rows="3" required placeholder="Écris ta réflexion..."></textarea>
                        @error('content') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">✅ Enregistrer la note</button>
                    <button type="button" class="btn btn-secondary" onclick="closeNoteForm()">❌ Annuler</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Section Notes -->
    <div class="mb-4">
        <h4>📝 Tes notes sur ce livre (dans {{ $journal->name }})</h4>

        @if($notes->count() > 0)
            <div class="row">
                @foreach($notes as $note)
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <blockquote class="blockquote mb-2 p-2 bg-warning bg-opacity-10 rounded">
                                    <p class="mb-1 fst-italic text-dark">« {{ $note->excerpt }} »</p>
                                </blockquote>
                                <p class="card-text">{{ $note->content }}</p>
                                <small class="text-muted">Ajouté le {{ $note->created_at->format('d/m/Y à H:i') }}</small>

                                <div class="mt-2">
                                    <form action="{{ route('notes.destroy', $note) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cette note ?')">🗑️ Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                Tu n’as pas encore ajouté de note sur ce livre.
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    const selectableText = document.getElementById('selectable-text');
    const noteButton = document.getElementById('note-button');
    const noteForm = document.getElementById('note-form');
    const excerptPreview = document.getElementById('excerpt-preview');
    const selectedExcerptInput = document.getElementById('selected-excerpt');

    let selectedText = '';

    // Détecter la sélection de texte
    selectableText.addEventListener('mouseup', function () {
        const selection = window.getSelection().toString().trim();
        if (selection.length > 0) {
            selectedText = selection;
            excerptPreview.textContent = `"${selectedText}"`;
            selectedExcerptInput.value = selectedText;
            noteButton.classList.remove('d-none');
        } else {
            noteButton.classList.add('d-none');
        }
    });

    // Ouvrir le formulaire
    function openNoteForm() {
        noteButton.classList.add('d-none');
        noteForm.classList.remove('d-none');
        document.getElementById('content').focus();
    }

    // Fermer le formulaire
    function closeNoteForm() {
        noteForm.classList.add('d-none');
    }

    // Réinitialiser si l'utilisateur clique ailleurs
    document.addEventListener('click', function (e) {
        if (!selectableText.contains(e.target) && !noteForm.contains(e.target)) {
            noteButton.classList.add('d-none');
        }
    });
</script>
@endpush

<style>
    /* Permettre la sélection dans le texte du livre */
    .selectable-text {
        -webkit-user-select: text;
        -moz-user-select: text;
        -ms-user-select: text;
        user-select: text;
    }

    /* Désactiver la sélection sur les boutons et formulaires */
    button, .btn, input, textarea, .card, .form-control {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Surlignage des extraits déjà annotés */
    .highlighted-excerpt {
        background-color: #fff3cd !important;
        padding: 0.1rem 0.3rem;
        border-radius: 0.2rem;
        box-shadow: 0 0 0 1px rgba(0,0,0,0.1) inset;
    }

    /* Empêcher que le surlignage casse la mise en page */
    .highlighted-excerpt * {
        background-color: transparent !important;
    }
</style>
@endsection