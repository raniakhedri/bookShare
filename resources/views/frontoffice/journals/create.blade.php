@extends('frontoffice.layouts.app')

@section('title', 'Create a Journal - Bookly')

@section('content')
<div class="journal-create-container">
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
                        <i class="bi bi-journal-plus"></i>
                    </div>
                    <h1 class="journal-title">Create New Journal</h1>
                    <p class="journal-subtitle">Craft your perfect reading companion</p>
                    <div class="header-divider"></div>
                </div>

                <!-- Form Card -->
                <div class="form-card" data-aos="zoom-in">
                    <div class="card-body">
                        <form action="{{ route('journals.store') }}" method="POST" class="journal-form">
                            @csrf

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
                                        placeholder="Ex: My 2024 Reading Challenge, Fantasy Books Collection..."
                                        required
                                        autofocus
                                        maxlength="100"
                                    >
                                </div>
                                <div class="form-text">
                                    <span id="charCount" class="char-count">0</span>/100 characters
                                </div>
                                @error('name')
                                    <div class="alert alert-error">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <!-- Journal Description -->
                            <div class="form-group">
                                <label for="description" class="form-label">
                                    <i class="bi bi-card-text me-2"></i>
                                    Description <span class="optional">(Optional)</span>
                                </label>
                                <div class="input-container">
                                    <textarea 
                                        name="description" 
                                        id="description" 
                                        class="form-control"
                                        rows="3"
                                        placeholder="What's the story behind this journal? Share your inspiration..."
                                        maxlength="255"
                                    ></textarea>
                                </div>
                                <div class="form-text">
                                    <span id="descCharCount" class="char-count">0</span>/255 characters
                                </div>
                            </div>

                            <!-- Color Selection -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-palette me-2"></i>
                                    Journal Color <span class="optional">(Optional)</span>
                                </label>
                                <div class="color-selection">
                                    <div class="color-option active" data-color="#667eea" style="background: #667eea;">
                                        <i class="bi bi-check"></i>
                                    </div>
                                    <div class="color-option" data-color="#f86d72" style="background: #f86d72;"></div>
                                    <div class="color-option" data-color="#4ecdc4" style="background: #4ecdc4;"></div>
                                    <div class="color-option" data-color="#45b7d1" style="background: #45b7d1;"></div>
                                    <div class="color-option" data-color="#96ceb4" style="background: #96ceb4;"></div>
                                    <div class="color-option" data-color="#feca57" style="background: #feca57;"></div>
                                    <input type="hidden" name="color" id="journalColor" value="#667eea">
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-create">
                                    <i class="bi bi-plus-circle"></i>
                                    <span>Create Journal</span>
                                    <div class="btn-spinner d-none">
                                        <div class="spinner"></div>
                                    </div>
                                </button>
                                <a href="{{ url('/book') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i>
                                    Back to Books
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
.journal-create-container {
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

.optional {
    color: var(--text-muted);
    font-weight: 400;
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

.input-container textarea.form-control {
    padding-left: 16px;
    padding-top: 12px;
    min-height: 100px;
    resize: vertical;
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

/* ===== COLOR SELECTION ===== */
.color-selection {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.color-option {
    width: 40px;
    height: 40px;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
    position: relative;
    border: 3px solid transparent;
    display: flex;
    align-items: center;
    justify-content: center;
}

.color-option:hover {
    transform: scale(1.1);
}

.color-option.active {
    border-color: rgba(0, 0, 0, 0.2);
    transform: scale(1.15);
}

.color-option.active i {
    color: white;
    font-size: 0.8rem;
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

/* ===== SPINNER ===== */
.btn-spinner .spinner {
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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
    
    .color-selection {
        justify-content: center;
    }
    
    .color-option {
        width: 35px;
        height: 35px;
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

    // Character counters
    const nameInput = document.getElementById('name');
    const charCount = document.getElementById('charCount');
    const descInput = document.getElementById('description');
    const descCharCount = document.getElementById('descCharCount');
    
    nameInput.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
    
    if (descInput) {
        descInput.addEventListener('input', function() {
            descCharCount.textContent = this.value.length;
        });
    }

    // Color selection
    const colorOptions = document.querySelectorAll('.color-option');
    const journalColorInput = document.getElementById('journalColor');
    
    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            colorOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            journalColorInput.value = this.getAttribute('data-color');
        });
    });

    // Form submission with loading state
    const form = document.querySelector('.journal-form');
    const submitBtn = form.querySelector('.btn-create');
    const btnSpinner = submitBtn.querySelector('.btn-spinner');
    const btnText = submitBtn.querySelector('span');
    
    form.addEventListener('submit', function() {
        btnSpinner.classList.remove('d-none');
        btnText.textContent = 'Creating...';
        submitBtn.disabled = true;
    });
});
</script>
@endpush