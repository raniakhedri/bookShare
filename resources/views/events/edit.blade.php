@extends('frontoffice.layouts.app')

@section('title', 'Modifier ' . $event->title . ' - ' . $group->name)

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('groups.index') }}">Groupes</a></li>
            <li class="breadcrumb-item"><a href="{{ route('groups.show', $group) }}">{{ $group->name }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('groups.events.show', [$group, $event]) }}">{{ $event->title }}</a></li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Modifier l'événement
                    </h3>
                    <a href="{{ route('groups.events.show', [$group, $event]) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Retour
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('groups.events.update', [$group, $event]) }}" method="POST" id="eventForm">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Informations de base -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="title" class="form-label">Titre de l'événement <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $event->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="type" class="form-label">Type d'événement <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Sélectionnez un type</option>
                                    @foreach($eventTypes as $key => $typeInfo)
                                        <option value="{{ $key }}" {{ old('type', $event->type) == $key ? 'selected' : '' }}>
                                            {{ $typeInfo['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dates et heures -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_datetime" class="form-label">Date et heure de début <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('start_datetime') is-invalid @enderror" 
                                       id="start_datetime" name="start_datetime" 
                                       value="{{ old('start_datetime', $event->start_datetime->format('Y-m-d\TH:i')) }}" required>
                                @error('start_datetime')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_datetime" class="form-label">Date et heure de fin</label>
                                <input type="datetime-local" class="form-control @error('end_datetime') is-invalid @enderror" 
                                       id="end_datetime" name="end_datetime" 
                                       value="{{ old('end_datetime', $event->end_datetime ? $event->end_datetime->format('Y-m-d\TH:i') : '') }}">
                                @error('end_datetime')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Statut de l'événement</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Brouillon</option>
                                <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Publié</option>
                                <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                                <option value="completed" {{ old('status', $event->status) == 'completed' ? 'selected' : '' }}>Terminé</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Localisation -->
                        <div class="mb-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="is_virtual" name="is_virtual" 
                                       value="1" {{ old('is_virtual', $event->is_virtual) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_virtual">
                                    <i class="fas fa-video me-1"></i>Événement virtuel
                                </label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6" id="location-field">
                                <label for="location" class="form-label">Lieu</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location', $event->location) }}" 
                                       placeholder="Adresse ou nom du lieu">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6" id="meeting-link-field" style="display: none;">
                                <label for="meeting_link" class="form-label">Lien de réunion</label>
                                <input type="url" class="form-control @error('meeting_link') is-invalid @enderror" 
                                       id="meeting_link" name="meeting_link" value="{{ old('meeting_link', $event->meeting_link) }}" 
                                       placeholder="https://meet.google.com/...">
                                @error('meeting_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Paramètres d'inscription -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="max_participants" class="form-label">Nombre maximum de participants</label>
                                <input type="number" class="form-control @error('max_participants') is-invalid @enderror" 
                                       id="max_participants" name="max_participants" value="{{ old('max_participants', $event->max_participants) }}" 
                                       min="1" placeholder="Laisser vide pour illimité">
                                @error('max_participants')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="requires_approval" 
                                           name="requires_approval" value="1" {{ old('requires_approval', $event->requires_approval) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="requires_approval">
                                        <i class="fas fa-check-circle me-1"></i>Approbation requise
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Ressources et prérequis -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="resources" class="form-label">Ressources fournies</label>
                                <textarea class="form-control @error('resources') is-invalid @enderror" 
                                          id="resources" name="resources" rows="3" 
                                          placeholder="Une ressource par ligne">{{ old('resources', is_array($event->resources) ? implode("\n", $event->resources) : '') }}</textarea>
                                @error('resources')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Listez les ressources qui seront fournies (une par ligne)</small>
                            </div>
                            <div class="col-md-6">
                                <label for="requirements" class="form-label">Prérequis</label>
                                <textarea class="form-control @error('requirements') is-invalid @enderror" 
                                          id="requirements" name="requirements" rows="3" 
                                          placeholder="Décrivez les prérequis éventuels">{{ old('requirements', $event->requirements) }}</textarea>
                                @error('requirements')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                <i class="fas fa-times me-1"></i>Annuler
                            </button>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Sauvegarder les modifications
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Informations sur l'événement -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations sur l'événement
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Créé le :</strong> {{ $event->created_at->format('d/m/Y à H:i') }}
                    </div>
                    <div class="mb-2">
                        <strong>Créateur :</strong> {{ $event->creator->name }}
                    </div>
                    <div class="mb-2">
                        <strong>Participants inscrits :</strong> {{ $event->participants->where('status', 'approved')->count() }}
                    </div>
                    @if($event->participants->where('status', 'pending')->count() > 0)
                        <div class="mb-2">
                            <strong>En attente d'approbation :</strong> 
                            <span class="badge bg-warning">{{ $event->participants->where('status', 'pending')->count() }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Participants en attente (si approbation requise) -->
            @if($event->requires_approval && $event->participants->where('status', 'pending')->count() > 0 && (auth()->id() === $event->creator_id || auth()->user()->can('moderate', $group)))
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-clock text-warning me-2"></i>
                            Demandes en attente
                        </h6>
                    </div>
                    <div class="card-body">
                        @foreach($event->participants->where('status', 'pending') as $participant)
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                                <div>
                                    <strong>{{ $participant->user->name }}</strong>
                                    @if($participant->registration_message)
                                        <br><small class="text-muted">{{ $participant->registration_message }}</small>
                                    @endif
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-success" 
                                            onclick="approveParticipant({{ $participant->id }})">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="rejectParticipant({{ $participant->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Actions avancées -->
            @if(auth()->id() === $event->creator_id || auth()->user()->can('moderate', $group))
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-cog me-2"></i>
                            Actions avancées
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($event->status === 'published' && $event->start_datetime->isPast() && $event->status !== 'completed')
                            <button type="button" class="btn btn-info btn-sm mb-2 w-100" onclick="markAsCompleted()">
                                <i class="fas fa-flag-checkered me-1"></i>Marquer comme terminé
                            </button>
                        @endif
                        
                        @if($event->status !== 'cancelled')
                            <button type="button" class="btn btn-warning btn-sm mb-2 w-100" onclick="cancelEvent()">
                                <i class="fas fa-ban me-1"></i>Annuler l'événement
                            </button>
                        @endif
                        
                        <button type="button" class="btn btn-danger btn-sm w-100" onclick="deleteEvent()">
                            <i class="fas fa-trash me-1"></i>Supprimer l'événement
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isVirtualCheckbox = document.getElementById('is_virtual');
    const locationField = document.getElementById('location-field');
    const meetingLinkField = document.getElementById('meeting-link-field');
    
    function toggleFields() {
        if (isVirtualCheckbox.checked) {
            locationField.style.display = 'none';
            meetingLinkField.style.display = 'block';
        } else {
            locationField.style.display = 'block';
            meetingLinkField.style.display = 'none';
        }
    }
    
    isVirtualCheckbox.addEventListener('change', toggleFields);
    toggleFields(); // Initial call
    
    // Transform resources textarea into array
    const resourcesTextarea = document.getElementById('resources');
    const form = document.getElementById('eventForm');
    
    form.addEventListener('submit', function() {
        if (resourcesTextarea.value.trim()) {
            const resources = resourcesTextarea.value.split('\n')
                .map(item => item.trim())
                .filter(item => item.length > 0);
            resourcesTextarea.value = JSON.stringify(resources);
        }
    });
});

function approveParticipant(participantId) {
    updateParticipantStatus(participantId, 'approved');
}

function rejectParticipant(participantId) {
    if (confirm('Êtes-vous sûr de vouloir rejeter cette demande ?')) {
        updateParticipantStatus(participantId, 'rejected');
    }
}

function updateParticipantStatus(participantId, status) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/groups/{{ $group->id }}/events/{{ $event->id }}/participants/${participantId}/status`;
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'PATCH';
    form.appendChild(methodField);
    
    const statusField = document.createElement('input');
    statusField.type = 'hidden';
    statusField.name = 'status';
    statusField.value = status;
    form.appendChild(statusField);
    
    document.body.appendChild(form);
    form.submit();
}

function markAsCompleted() {
    if (confirm('Marquer cet événement comme terminé ?')) {
        updateEventStatus('completed');
    }
}

function cancelEvent() {
    if (confirm('Êtes-vous sûr de vouloir annuler cet événement ? Cette action notifiera tous les participants.')) {
        updateEventStatus('cancelled');
    }
}

function updateEventStatus(status) {
    document.getElementById('status').value = status;
    document.getElementById('eventForm').submit();
}

function deleteEvent() {
    if (confirm('Êtes-vous sûr de vouloir supprimer définitivement cet événement ? Cette action est irréversible.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("groups.events.destroy", [$group, $event]) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection