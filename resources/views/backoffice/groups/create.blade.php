@extends('backoffice.layouts.user_type.auth')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-400 text-white">
                <div class="card-header bg-transparent text-center py-4 rounded-top border-0">
                    <h3 class="mb-0 font-weight-bold text-white drop-shadow-lg">Add a Group</h3>
                </div>
                <div class="card-body bg-white/80 rounded-bottom">
                    <form action="{{ route('admin.groups.store') }}" method="POST" class="row g-4" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-6">
                            <label for="name" class="form-label text-indigo-700">Name</label>
                            <input type="text" class="form-control rounded-pill shadow-sm border-indigo-200 focus:border-pink-400" id="name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="theme" class="form-label text-pink-700">Theme</label>
                            <input type="text" class="form-control rounded-pill shadow-sm border-pink-200 focus:border-indigo-400" id="theme" name="theme" required>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label text-purple-700">Description</label>
                            <textarea class="form-control rounded shadow-sm border-purple-200 focus:border-pink-400" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="image" class="form-label text-indigo-700">Image du groupe</label>
                            <input type="file" class="form-control rounded shadow-sm border-indigo-200 focus:border-pink-400" id="image" name="image" accept="image/*">
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                            <button type="submit" class="btn btn-gradient px-4 rounded-pill shadow" style="background: linear-gradient(90deg,#7f53ac,#657ced,#ff6a88); color: #fff; border: none;">Create Group</button>
                            <a href="{{ route('admin.groups') }}" class="btn btn-light px-4 rounded-pill shadow border">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles cohérents avec le formulaire books */
.form-control, .form-select {
    border: 1px solid;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    box-shadow: 0 0 0 0.2rem rgba(102, 124, 237, 0.25);
    border-color: #ff6a88;
    outline: none;
}

.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.text-indigo-700 { color: #4338ca; }
.text-pink-700 { color: #be185d; }
.text-purple-700 { color: #7e22ce; }

.border-indigo-200 { border-color: #c7d2fe !important; }
.border-pink-200 { border-color: #fbcfe8 !important; }
.border-purple-200 { border-color: #e9d5ff !important; }

.btn-gradient {
    background: linear-gradient(90deg, #7f53ac, #657ced, #ff6a88);
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 124, 237, 0.4);
}

.btn-light {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-light:hover {
    background: #e9ecef;
    transform: translateY(-1px);
}

.rounded-pill {
    border-radius: 50rem !important;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.shadow {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.bg-white\/80 {
    background-color: rgba(255, 255, 255, 0.8) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .container.py-4 {
        padding: 1rem;
    }
    
    .col-lg-8 {
        padding: 0;
    }
    
    .row.g-4 {
        margin: 0;
    }
    
    .col-md-6, .col-12 {
        padding: 0.5rem;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .d-flex.justify-content-end {
        flex-direction: column;
    }
}
</style>

<script>
// Animation pour les champs du formulaire
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.form-control, .form-select');
    
    inputs.forEach(input => {
        // Effet au focus
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        // Effet à la perte du focus
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.parentElement.classList.remove('focused');
            }
        });
        
        // Vérifier si le champ a déjà une valeur au chargement
        if (input.value !== '') {
            input.parentElement.classList.add('focused');
        }
    });
});
</script>
@endsection