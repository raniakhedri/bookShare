@extends('backoffice.layouts.user_type.auth')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom pb-2">
                    <h5 class="mb-0 fw-bold text-dark">✏️ Edit Category</h5>
                </div>
                <div class="card-body bg-light rounded-bottom">
                    <form action="{{ route('categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label text-primary">Category name</label>
                            <input type="text" class="form-control rounded-pill shadow-sm border-primary" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                        </div>
                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
