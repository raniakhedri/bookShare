@extends('frontoffice.layouts.app')

@section('title', 'Edit ' . $journal->name . ' - Bookly')

@section('content')
<div class="journal-edit-container">
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
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                
                <!-- Header Section -->
                <div class="text-center mb-5 animate-fade-in" data-aos="fade-up">
                    <div class="journal-icon-container">
                        <div class="icon-glow"></div>
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <h1 class="journal-title">Edit Journal</h1>
                    <p class="journal-subtitle">Update your reading companion</p>
                    <div class="header-divider"></div>
                </div>

                <!-- Form Card -->
                <div class="form-card" data-aos="zoom-in">
                    <div class="card-body">
                        <form action="{{ route('journals.update', $journal) }}" method="POST" class="journal-form">
                            @csrf
                            @method('PUT')

                            <!-- Journal Name Field -->
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="bi bi-pencil me-2"></i>
                                    Journal Name <span class="required">*</span>
                                </label>
                                <div class="input-container">
                                    <div class="input-icon">
                                        <i class="bi bi-journal-text"></i>
                                    </div>
                                    <input 
                                        type="text" 
                                        name="name" 
                                        id="name" 
                                        class="form-control"
                                        placeholder="Enter journal name"
                                        value="{{ old('name', $journal->name) }}"
                                        required
                                        autofocus
                                        maxlength="100"
                                    >
                                </div>
                                <div class="form-text">
                                    <span id="charCount" class="char-count">{{ strlen($journal->name) }}</span>/100 characters
                                </div>
                                @error('name')
                                    <div class="alert alert-error">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i>
                                    Update Journal
                                </button>
                                <a href="{{ route('journals.show', $journal) }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i>
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

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
.journal-edit-container {
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

/* ===== FORM CARD ===== */
.form-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(15px);
    border-radius: var(--border-radius-lg);
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: var(--transition);
    box-shadow: var(--shadow-medium);
    overflow: hidden;
}

.form-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-heavy);
    background: rgba(255, 255, 255, 0.95);
}

.card-body {
    padding: 2rem;
}

/* ===== FORM ELEMENTS ===== */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    color: var(--text-dark);
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
}

.required {
    color: var(--secondary-color);
}

.input-container {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    z-index: 2;
}

.input-container .form-control {
    padding-left: 50px;
    border: 1.5px solid rgba(0, 0, 0, 0.08);
    border-radius: var(--border-radius);
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    transition: var(--transition);
    font-size: 1rem;
}

.input-container .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
    background: rgba(255, 255, 255, 0.95);
}

.form-text {
    display: flex;
    justify-content: flex-end;
    margin-top: 0.5rem;
    font-size: 0.85rem;
    color: var(--text-muted);
}

.char-count {
    font-weight: 600;
    color: var(--text-light);
}

/* ===== ALERT STYLES ===== */
.alert-error {
    background: rgba(220, 53, 69, 0.08);
    border: 1px solid rgba(220, 53, 69, 0.2);
    border-radius: var(--border-radius);
    color: #dc3545;
    font-size: 0.9rem;
    padding: 0.75rem 1rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    backdrop-filter: blur(10px);
}

/* ===== FORM ACTIONS ===== */
.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
}

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
    flex: 1;
    justify-content: center;
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

.btn-secondary {
    background: rgba(108, 117, 125, 0.1);
    color: var(--text-light);
    border: 1px solid rgba(108, 117, 125, 0.2);
}

.btn-secondary:hover {
    background: rgba(108, 117, 125, 0.15);
    color: var(--text-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow-light);
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
    
    .card-body {
        padding: 1.5rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
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
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS animations
    AOS.init({ 
        duration: 800, 
        once: true, 
        offset: 50 
    });

    // Character counter
    const nameInput = document.getElementById('name');
    const charCount = document.getElementById('charCount');
    
    nameInput.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
});
</script>
@endpush