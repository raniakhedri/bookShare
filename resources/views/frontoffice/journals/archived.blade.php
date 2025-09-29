@extends('frontoffice.layouts.app')

@section('title', 'Archived Books - ' . $journal->name . ' - Bookly')

@section('content')
<div class="archived-books-container">
    <!-- Animated Background -->
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
                <i class="bi bi-archive"></i>
            </div>
            <h1 class="journal-title">Archived Books</h1>
            <p class="journal-subtitle">{{ $journal->name }}</p>
            <div class="header-divider"></div>

            <!-- Stats -->
            <div class="journal-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $archivedBooks->count() }}</span>
                    <span class="stat-label">Archived Books</span>
                </div>
            </div>
        </div>

        <!-- Archived Books List -->
        <div class="archived-content">
            @if($archivedBooks->count() > 0)
                <div class="books-grid">
                    @foreach($archivedBooks as $book)
                        <div class="book-card" data-aos="zoom-in">
                            <div class="card-header">
                                <span class="status-badge archived">
                                    <i class="bi bi-archive"></i> Archived
                                </span>
                            </div>
                            <div class="card-body">
                                <h3 class="book-title">{{ $book->title }}</h3>
                                <p class="book-description">
                                    {{ Str::limit($book->description, 120) }}
                                </p>
                            </div>
                            <div class="card-actions">
                                <form action="{{ route('journals.unarchive-book', [$journal->id, $book->id]) }}" method="POST" class="action-form">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-action btn-unarchive">
                                        <i class="bi bi-arrow-counterclockwise"></i> Unarchive
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
                        <i class="bi bi-archive"></i>
                    </div>
                    <h3>No Archived Books</h3>
                    <p class="empty-description">
                        There are no archived books in this journal. Books you archive will appear here.
                    </p>
                </div>
            @endif

            <!-- Back Button -->
            <div class="navigation-section">
                <a href="{{ route('journals.show', $journal) }}" class="btn-navigation">
                    <i class="bi bi-arrow-left"></i> Back to Journal
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
.archived-books-container {
    position: relative;
    min-height: 100vh;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef, #dee2e6);
    overflow: hidden;
}

/* ===== ANIMATED BACKGROUND ===== */
.animated-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}

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

.journal-subtitle {
    color: var(--text-light);
    font-size: 1.2rem;
    margin-bottom: 1.5rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.header-divider {
    width: 60px; 
    height: 3px;
    margin: 1.5rem auto;
    border-radius: 3px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.journal-stats {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 2rem;
    margin-top: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
    line-height: 1;
}

.stat-label {
    color: var(--text-light);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* ===== BOOKS GRID ===== */
.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.book-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(15px);
    border-radius: var(--border-radius-lg);
    border: 1px solid rgba(255, 255, 255, 0.3);
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
    background: rgba(255, 255, 255, 0.95);
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

.card-body {
    padding: 1rem 1.25rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.book-title {
    color: var(--text-dark);
    font-size: 1.25rem;
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: 0.75rem;
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
}

.action-form {
    width: 100%;
}

.btn-action {
    width: 100%;
    padding: 0.75rem;
    border: none;
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    font-weight: 500;
    transition: var(--transition);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
}

.btn-unarchive {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.btn-unarchive:hover {
    background: #28a745;
    color: white;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px);
    border-radius: var(--border-radius-lg);
    padding: 3rem 2rem;
    text-align: center;
    max-width: 500px;
    margin: 0 auto 3rem;
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: var(--shadow-medium);
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.empty-icon i {
    font-size: 2.5rem;
    color: var(--primary-color);
}

.empty-state h3 {
    color: var(--text-dark);
    margin-bottom: 1rem;
    font-weight: 600;
}

.empty-description {
    color: var(--text-light);
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 0;
}

/* ===== NAVIGATION ===== */
.navigation-section {
    text-align: center;
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
    
    .journal-subtitle {
        font-size: 1.1rem;
    }
    
    .books-grid {
        grid-template-columns: 1fr;
    }
    
    .empty-state {
        padding: 2rem 1.5rem;
        margin: 0 auto 2rem;
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
    
    .journal-stats {
        flex-direction: column;
        gap: 1rem;
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