@extends('frontoffice.layouts.app')

@section('title', $journal->name . ' - Bookly')

@section('content')
<div class="journal-show-container">
    <!-- Animated Background (conservé) -->
    <div class="animated-background">
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>
        <div class="gradient-overlay"></div>
    </div>

    <div class="container py-5 position-relative" style="z-index: 2;">
        
        <!-- Header Section -->
        <div class="text-center mb-5 animate-fade-in" data-aos="fade-up">
            <div class="journal-icon-container">
                <div class="icon-glow"></div>
                <i class="bi bi-journal-bookmark"></i>
            </div>
            <h1 class="journal-title">{{ $journal->name }}</h1>
            <div class="journal-meta">
                <span class="meta-item">
                    <i class="bi bi-book me-1"></i> 
                    {{ $books->count() }} book{{ $books->count() != 1 ? 's' : '' }}
                </span>
                @if($archivedCount > 0)
                <span class="meta-divider">•</span>
                <span class="meta-item">
                    <i class="bi bi-archive me-1"></i> 
                    {{ $archivedCount }} archived
                </span>
                @endif
            </div>
            <div class="header-divider"></div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('journals.edit', $journal) }}" class="btn btn-action-edit">
                    <i class="bi bi-pencil"></i> 
                    <span>Edit Journal</span>
                </a>
                <a href="{{ route('journals.archived', $journal) }}" class="btn btn-action-archived">
                    <i class="bi bi-archive"></i> 
                    <span>View Archived</span>
                    @if($archivedCount > 0)
                        <span class="badge-count">{{ $archivedCount }}</span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Book List Section -->
        @if($books->count() > 0)
            <div class="book-grid">
                @foreach($books as $book)
                    <div class="book-card" data-aos="zoom-in">
                        <div class="card-header">
                            @if($book->pivot->archived)
                                <span class="status-badge archived">
                                    <i class="bi bi-archive"></i> Archived
                                </span>
                            @else
                                <span class="status-badge active">
                                    <i class="bi bi-book"></i> Active
                                </span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h3 class="book-title">
                                <a href="{{ url('/livre/' . $book->id) }}">

                                    {{ $book->title }}
                                </a>
                            </h3>
                            <p class="book-description">
                                {{ Str::limit($book->description, 120) }}
                            </p>
                        </div>
                        <div class="card-actions">
                            @if(!$book->pivot->archived)
                                <form action="{{ route('journals.archive-book', [$journal->id, $book->id]) }}" method="POST" class="action-form">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-action btn-archive">
                                        <i class="bi bi-archive"></i> Archive
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('journals.detach-book', [$journal->id, $book->id]) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to remove this book from the journal?');" class="action-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action btn-remove">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state" data-aos="fade-up">
                <div class="empty-icon">
                    <i class="bi bi-bookshelf"></i>
                </div>
                <h3>No books in this journal</h3>
                <p>Start building your collection by adding books from your library.</p>
                <a href="{{ route('books.index') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Your First Book
                </a>
            </div>
        @endif

        <!-- Navigation -->
        <div class="navigation-section">
            <a href="{{ route('books.index') }}" class="btn-navigation">
                <i class="bi bi-arrow-left"></i> Back to Library
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
/* ===== VARIABLES ===== */
:root {
    --primary-color: #667eea;
    --primary-dark: #5a6fd8;
    --secondary-color: #f86d72;
    --secondary-dark: #e7585d;
    --text-dark: #2d3748;
    --text-light: #6c757d;
    --text-muted: #a0aec0;
    --bg-light: #f8f9fa;
    --bg-white: #ffffff;
    --border-light: rgba(255, 255, 255, 0.3);
    --shadow-light: 0 4px 6px rgba(0, 0, 0, 0.05);
    --shadow-medium: 0 10px 15px rgba(0, 0, 0, 0.08);
    --shadow-heavy: 0 20px 25px rgba(0, 0, 0, 0.1);
    --border-radius: 12px;
    --border-radius-lg: 20px;
    --transition: all 0.3s ease;
}

/* ===== BASE STYLES ===== */
.journal-show-container {
    position: relative;
    min-height: 100vh;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef, #dee2e6);
    overflow: hidden;
}

/* ===== ANIMATED BACKGROUND (conservé) ===== */
.floating-shapes .shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(102, 126, 234, 0.05);
    animation: float 6s ease-in-out infinite;
}
.shape-1 { width: 100px; height: 100px; top: 10%; left: 10%; }
.shape-2 { width: 80px; height: 80px; top: 70%; left: 15%; animation-delay: 2s; }
.shape-3 { width: 150px; height: 150px; top: 20%; right: 10%; animation-delay: 3s; }
.shape-4 { width: 120px; height: 120px; bottom: 10%; right: 20%; animation-delay: 4s; }

.gradient-overlay {
    position: absolute;
    width: 100%; height: 100%;
    background: radial-gradient(circle at 20% 30%, rgba(248, 109, 114, 0.08), transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(102, 126, 234, 0.08), transparent 50%);
}

/* ===== HEADER SECTION ===== */
.journal-icon-container {
    width: 90px; 
    height: 90px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border-radius: 25px;
    display: flex; 
    align-items: center; 
    justify-content: center;
    margin: 0 auto 1.5rem;
    box-shadow: var(--shadow-heavy);
    position: relative;
    transition: var(--transition);
}

.journal-icon-container:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 30px rgba(102, 126, 234, 0.3);
}

.icon-glow {
    position: absolute;
    inset: -10px;
    background: rgba(102, 126, 234, 0.2);
    border-radius: 30px;
    filter: blur(15px);
    animation: pulse 2s ease-in-out infinite;
}

.journal-icon-container i { 
    font-size: 2.2rem; 
    color: white; 
}

.journal-title {
    color: var(--text-dark);
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    letter-spacing: -0.5px;
}

.journal-meta {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.meta-item {
    color: var(--text-light);
    font-size: 1rem;
    display: flex;
    align-items: center;
}

.meta-divider {
    color: var(--text-muted);
}

.header-divider {
    width: 60px; 
    height: 3px;
    margin: 1.5rem auto;
    border-radius: 3px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

/* ===== ACTION BUTTONS ===== */
.action-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 2rem;
    flex-wrap: wrap;
}

.btn-action-edit, .btn-action-archived {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius);
    font-weight: 500;
    transition: var(--transition);
    text-decoration: none;
    border: 1px solid;
}

.btn-action-edit {
    color: var(--primary-color);
    border-color: var(--primary-color);
    background: rgba(102, 126, 234, 0.05);
}

.btn-action-edit:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
}

.btn-action-archived {
    color: var(--text-light);
    border-color: var(--text-light);
    background: rgba(108, 117, 125, 0.05);
    position: relative;
}

.btn-action-archived:hover {
    background: var(--text-light);
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
}

.badge-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background: var(--secondary-color);
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
}

/* ===== BOOK GRID ===== */
.book-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.book-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(15px);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    transition: var(--transition);
    box-shadow: var(--shadow-light);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.book-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-heavy);
}

.card-header {
    padding: 1rem 1.25rem 0;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge.archived {
    background: rgba(108, 117, 125, 0.1);
    color: var(--text-light);
}

.status-badge.active {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.card-body {
    padding: 1rem 1.25rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.book-title {
    margin-bottom: 0.75rem;
}

.book-title a {
    color: var(--text-dark);
    text-decoration: none;
    font-size: 1.25rem;
    font-weight: 600;
    line-height: 1.4;
    transition: var(--transition);
}

.book-title a:hover {
    color: var(--primary-color);
}

.book-description {
    color: var(--text-light);
    font-size: 0.9rem;
    line-height: 1.5;
    flex-grow: 1;
}

.card-actions {
    padding: 1rem 1.25rem;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    gap: 0.75rem;
}

.action-form {
    flex: 1;
}

.btn-action {
    width: 100%;
    padding: 0.5rem;
    border: none;
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    font-weight: 500;
    transition: var(--transition);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
}

.btn-archive {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}

.btn-archive:hover {
    background: #ffc107;
    color: white;
}

.btn-remove {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.btn-remove:hover {
    background: #dc3545;
    color: white;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    margin: 2rem 0;
}

.empty-icon {
    font-size: 4rem;
    color: var(--text-muted);
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    color: var(--text-dark);
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.empty-state p {
    color: var(--text-light);
    margin-bottom: 2rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* ===== NAVIGATION ===== */
.navigation-section {
    text-align: center;
    margin-top: 3rem;
}

.btn-navigation {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-light);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius);
}

.btn-navigation:hover {
    color: var(--primary-color);
    background: rgba(102, 126, 234, 0.05);
}

/* ===== ANIMATIONS ===== */
@keyframes float {
    0%,100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}

@keyframes pulse {
    0%,100% { opacity: 0.5; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.05); }
}

.animate-fade-in { 
    animation: fadeInUp 0.8s ease-out; 
}

@keyframes fadeInUp {
    from { 
        opacity: 0; 
        transform: translateY(40px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .journal-title {
        font-size: 2rem;
    }
    
    .action-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-action-edit, .btn-action-archived {
        width: 100%;
        max-width: 250px;
        justify-content: center;
    }
    
    .book-grid {
        grid-template-columns: 1fr;
    }
    
    .journal-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .meta-divider {
        display: none;
    }
}

@media (max-width: 576px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .journal-icon-container {
        width: 70px;
        height: 70px;
    }
    
    .journal-icon-container i {
        font-size: 1.8rem;
    }
    
    .journal-title {
        font-size: 1.75rem;
    }
    
    .card-actions {
        flex-direction: column;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({ 
        duration: 800, 
        once: true, 
        offset: 50 
    });
});
</script>
@endpush