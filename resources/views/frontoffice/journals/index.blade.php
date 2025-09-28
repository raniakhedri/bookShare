@extends('frontoffice.layouts.app')

@section('title', 'My Journals - Bookly')

@section('content')
<div class="journals-index-container">
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
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>
            <h1 class="journal-title">My Reading Journals</h1>
            <p class="journal-subtitle">Curate your literary journey with beautifully organized journals</p>
            
            <!-- Stats -->
            <div class="journal-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $journals->count() }}</span>
                    <span class="stat-label">Journals</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <span class="stat-number">{{ $totalBooks ?? 0 }}</span>
                    <span class="stat-label">Books Total</span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="journal-content">
            @if($journals->count() > 0)
                <!-- Journals Grid -->
                <div class="journals-grid">
                    @foreach($journals as $journal)
                        <div class="journal-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <a href="{{ route('journals.show', $journal) }}" class="card-link">
                                <div class="card-header">
                                    <div class="journal-icon">
                                        <i class="bi bi-journal-text"></i>
                                    </div>
                                    <div class="journal-badge">
                                        <span class="book-count">{{ $journal->books->count() }}</span>
                                        <span class="book-label">books</span>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <h3 class="journal-name">{{ $journal->name }}</h3>
                                    @if($journal->description)
                                        <p class="journal-description">{{ Str::limit($journal->description, 100) }}</p>
                                    @endif
                                </div>
                                
                                <div class="card-footer">
                                    <div class="journal-meta">
                                        <i class="bi bi-clock"></i>
                                        <span>Updated {{ $journal->updated_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="journal-arrow">
                                        <i class="bi bi-arrow-right"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Create New Journal Section (Reduced) -->
                <div class="create-section text-center mt-4" data-aos="fade-up">
                    <div class="create-card">
                        <div class="create-content">
                            <i class="bi bi-plus-circle create-icon-small"></i>
                            <div class="create-text">
                                <h4>Create New Journal</h4>
                                <p class="create-description">Organize your books by theme, project, or reading goals</p>
                            </div>
                        </div>
                        <a href="{{ route('journals.create') }}" class="btn btn-outline-primary btn-sm">
                            Create
                        </a>
                    </div>
                </div>

            @else
                <!-- Empty State -->
                <div class="empty-state" data-aos="fade-up">
                    <div class="empty-icon">
                        <i class="bi bi-journal-plus"></i>
                    </div>
                    <h3>Start Your Reading Journey</h3>
                    <p class="empty-description">
                        Create your first journal to organize books by theme, project, or reading goals. 
                        Keep your reading experience organized and meaningful.
                    </p>
                    
                    <div class="features-grid">
                        <div class="feature-item">
                            <i class="bi bi-collection"></i>
                            <span>Organize by themes</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-tags"></i>
                            <span>Add custom tags</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-bar-chart"></i>
                            <span>Track progress</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('journals.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>
                        Create First Journal
                    </a>
                </div>
            @endif
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
.journals-index-container {
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
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
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

.stat-divider {
    width: 1px;
    height: 40px;
    background: var(--text-muted);
    opacity: 0.3;
}

/* ===== JOURNALS GRID ===== */
.journals-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.journal-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(15px);
    border-radius: var(--border-radius-lg);
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: var(--transition);
    box-shadow: var(--shadow-light);
    overflow: hidden;
    height: 100%;
}

.journal-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-heavy);
    background: rgba(255, 255, 255, 0.95);
}

.card-link {
    text-decoration: none;
    display: block;
    height: 100%;
    padding: 1.5rem;
    color: inherit;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.journal-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.journal-icon i {
    color: white;
    font-size: 1.3rem;
}

.journal-badge {
    background: rgba(102, 126, 234, 0.1);
    padding: 0.5rem 0.8rem;
    border-radius: 10px;
    text-align: center;
}

.book-count {
    display: block;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary-color);
    line-height: 1;
}

.book-label {
    color: var(--text-light);
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-body {
    flex: 1;
    margin-bottom: 1.5rem;
}

.journal-name {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.8rem;
    line-height: 1.3;
}

.journal-description {
    color: var(--text-light);
    font-size: 0.9rem;
    line-height: 1.5;
    margin: 0;
}

.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
}

.journal-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-muted);
    font-size: 0.8rem;
}

.journal-arrow {
    width: 30px;
    height: 30px;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
}

.journal-card:hover .journal-arrow {
    background: var(--primary-color);
    transform: translateX(5px);
}

.journal-card:hover .journal-arrow i {
    color: white;
}

.journal-arrow i {
    color: var(--primary-color);
    font-size: 0.8rem;
    transition: var(--transition);
}

/* ===== CREATE SECTION (REDUCED) ===== */
.create-section {
    margin-top: 2rem;
}

.create-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: var(--shadow-light);
    max-width: 500px;
    margin: 0 auto;
    transition: var(--transition);
}

.create-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
}

.create-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.create-icon-small {
    font-size: 2rem;
    color: var(--primary-color);
}

.create-text h4 {
    color: var(--text-dark);
    margin-bottom: 0.25rem;
    font-size: 1.1rem;
}

.create-description {
    color: var(--text-light);
    font-size: 0.85rem;
    margin: 0;
    line-height: 1.4;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px);
    border-radius: var(--border-radius-lg);
    padding: 3rem 2rem;
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: var(--shadow-medium);
}

.empty-icon {
    width: 100px;
    height: 100px;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.empty-icon i {
    font-size: 3rem;
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
    margin-bottom: 2rem;
    max-width: 450px;
    margin-left: auto;
    margin-right: auto;
}

.features-grid {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin: 2rem 0;
    flex-wrap: wrap;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary-color);
    font-weight: 500;
    padding: 0.5rem 1rem;
    background: rgba(102, 126, 234, 0.05);
    border-radius: var(--border-radius);
}

/* ===== BUTTONS ===== */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius);
    font-weight: 500;
    transition: var(--transition);
    text-decoration: none;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    color: white;
}

.btn-outline-primary {
    background: transparent;
    color: var(--primary-color);
    border: 1px solid var(--primary-color);
}

.btn-outline-primary:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-1px);
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
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
    
    .journal-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .stat-divider {
        display: none;
    }
    
    .journals-grid {
        grid-template-columns: 1fr;
    }
    
    .features-grid {
        flex-direction: column;
        gap: 1rem;
    }
    
    .create-card {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .create-content {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .empty-state {
        padding: 2rem 1.5rem;
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
    
    .card-link {
        padding: 1.25rem;
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