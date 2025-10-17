@extends('frontoffice.layouts.app')

@section('title', 'Ajouter au Journal - Bookly')

@section('content')
<div class="container-fluid py-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                
                <!-- En-tête -->
                <div class="text-center mb-5 animate-fade-in">
                    <div class="icon-container bg-primary-gradient">
                        <i class="bi bi-journal-plus text-white" style="font-size: 2rem;"></i>
                    </div>
                    <h1 class="page-title display-6">Add to Your Journal</h1>
                    <p class="page-subtitle">Add "<strong class="text-primary">{{ $book->title }}</strong>" to your reading journal</p>
                </div>

                <!-- Carte principale -->
                <div class="card journal-card animate-fade-in mb-4">
                    <div class="card-body p-4 p-md-5">
                        
                        @if($journals->count() > 0)
                            <form action="{{ route('books.store-in-journal', $book) }}" method="POST">
                                @csrf
                                
                                <!-- Sélection du journal -->
                                <div class="mb-4">
                                    <label for="journal_id" class="form-label">
                                        <i class="bi bi-journal-text me-2"></i>
                                        Choose a Journal <span class="text-danger">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <select name="journal_id" id="journal_id" required 
                                                class="form-select form-select-lg">
                                            <option value="">-- Select a journal --</option>
                                            @foreach($journals as $journal)
                                                <option value="{{ $journal->id }}">{{ $journal->name }}</option>
                                            @endforeach
                                        </select>
                                    
                                    </div>
                                </div>

                                <!-- Informations du livre -->
                                <div class="book-preview mb-4 p-3 rounded-3 bg-light">
                                    <div class="d-flex align-items-center gap-3">
                                        @if($book->image)
                                            <img src="{{ asset('storage/' . $book->image) }}" 
                                                 alt="{{ $book->title }}"
                                                 class="rounded-2" 
                                                 style="width: 60px; height: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded-2 d-flex align-items-center justify-content-center"
                                                 style="width: 60px; height: 80px;">
                                                <i class="bi bi-book text-white" style="font-size: 1.5rem;"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">{{ $book->title }}</h6>
                                            <p class="mb-1 text-muted small">{{ $book->author }}</p>
                                            <span class="badge {{ $book->availability ? 'bg-success' : 'bg-secondary' }} small">
                                                {{ $book->availability ? 'Available' : 'Unavailable' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Boutons d'action -->
                                <div class="d-flex flex-column flex-sm-row gap-3">
                                    <button type="submit" class="btn btn-primary flex-fill d-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-plus-circle"></i>
                                        Add to Journal
                                    </button>
                                    
                                    <a href="{{ url('/book') }}" class="btn btn-outline-secondary flex-fill d-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-arrow-left"></i>
                                        Back to Books
                                    </a>
                                </div>
                            </form>
                        @else
                            <!-- Aucun journal disponible -->
                            <div class="text-center py-4">
                                <div class="empty-state mb-4">
                                    <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="text-dark mb-3">No Journals Available</h5>
                                <p class="text-muted mb-4">You need to create a journal before you can add books to it.</p>
                                
                                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                                    <a href="{{ route('journals.create') }}" 
                                       class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-plus-circle"></i>
                                        Create First Journal
                                    </a>
                                    
                                    <a href="{{ url('/book') }}" 
                                       class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-arrow-left"></i>
                                        Back to Books
                                    </a>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Section d'aide -->
                <div class="animate-fade-in">
                    <div class="card info-card">
                        <div class="card-body p-3 d-flex align-items-start">
                            <i class="bi bi-lightbulb text-warning me-3 mt-1" style="font-size: 1.2rem;"></i>
                            <div>
                                <h6 class="card-title mb-1 text-warning">Did You Know?</h6>
                                <p class="card-text small mb-0">
                                    Adding books to journals helps you organize your reading by themes, 
                                    projects, or time periods. You can add notes and track your progress for each book.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .container-fluid {
        padding: 0;
    }
    
    .journal-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .journal-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    }
    
    .icon-container {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .bg-primary-gradient {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        padding: 12px 16px;
        border: 1.5px solid #e3e6f0;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
    }
    
    .btn-outline-secondary {
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-outline-secondary:hover {
        transform: translateY(-2px);
        background-color: #f8f9fa;
    }
    
    .info-card {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: none;
        border-radius: 12px;
        border-left: 4px solid #ffc107;
    }
    
    .page-title {
        color: #2e3a59;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .page-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
    }
    
    .form-label {
        font-weight: 600;
        color: #2e3a59;
        margin-bottom: 8px;
    }
    
    .input-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        pointer-events: none;
    }
    
    .book-preview {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .book-preview:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
        transform: translateX(5px);
    }
    
    .empty-state {
        opacity: 0.7;
        transition: opacity 0.3s ease;
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
    
    .animate-fade-in {
        animation: fadeInUp 0.6s ease-out;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .icon-container {
            width: 70px;
            height: 70px;
        }
        
        .btn-primary, .btn-outline-secondary {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .book-preview {
            text-align: center;
        }
        
        .container-fluid {
            padding: 1rem;
        }
    }
    
    /* Centrage amélioré */
    .container {
        max-width: 1200px;
    }
    
    .col-xl-6 {
        max-width: 550px;
    }
</style>
@endpush

@push('scripts')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endpush