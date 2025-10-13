@extends('backoffice.layouts.user_type.auth')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-400 text-white">
                <div class="card-header bg-transparent text-center py-4 rounded-top border-0">
                    <h3 class="mb-0 font-weight-bold text-white drop-shadow-lg">Edit Book</h3>
                </div>
                <div class="card-body bg-white/80 rounded-bottom">
                    <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data" class="row g-4">
                        @csrf
                        @method('PUT')
                        <div class="col-md-6">
                            <label for="title" class="form-label text-indigo-700">Title</label>
                            <input type="text" class="form-control rounded-pill shadow-sm border-indigo-200 focus:border-pink-400" id="title" name="title" value="{{ old('title', $book->title) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="author" class="form-label text-pink-700">Author</label>
                            <input type="text" class="form-control rounded-pill shadow-sm border-pink-200 focus:border-indigo-400" id="author" name="author" value="{{ old('author', $book->author) }}" required>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label text-purple-700">Description</label>
                            <textarea class="form-control rounded shadow-sm border-purple-200 focus:border-pink-400" id="description" name="description" rows="3">{{ old('description', $book->description) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="category_id" class="form-label text-indigo-700">Category</label>
                            <select class="form-select rounded-pill shadow-sm border-indigo-200 focus:border-pink-400" id="category_id" name="category_id" required>
                                <option value="">-- Choose a category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $book->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="condition" class="form-label text-pink-700">Condition</label>
                            <select class="form-select rounded-pill shadow-sm border-pink-200 focus:border-indigo-400" id="condition" name="condition" required>
                                <option value="New" {{ $book->condition == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Good" {{ $book->condition == 'Good' ? 'selected' : '' }}>Good</option>
                                <option value="Used" {{ $book->condition == 'Used' ? 'selected' : '' }}>Used</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="availability" class="form-label text-purple-700">Availability</label>
                            <select class="form-select rounded-pill shadow-sm border-purple-200 focus:border-pink-400" id="availability" name="availability">
                                <option value="1" {{ $book->availability ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !$book->availability ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="publication_year" class="form-label text-indigo-700">Publication Date</label>
                            <input type="date" class="form-control rounded-pill shadow-sm border-indigo-200 focus:border-pink-400" id="publication_year" name="publication_year" value="{{ old('publication_year', $book->publication_year ? date('Y-m-d', strtotime($book->publication_year)) : '') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="image" class="form-label text-pink-700">Image</label>
                            <input type="file" class="form-control rounded-pill shadow-sm border-pink-200 focus:border-indigo-400" id="image" name="image">
                            @if($book->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $book->image) }}" alt="Book image" style="width:80px; height:auto; border-radius:8px; box-shadow:0 2px 8px #ccc;">
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="file" class="form-label text-purple-700">Book PDF File</label>
                            <input type="file" class="form-control rounded-pill shadow-sm border-purple-200 focus:border-pink-400" id="file" name="file" accept="application/pdf">
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                            <button type="submit" class="btn btn-gradient px-4 rounded-pill shadow" style="background: linear-gradient(90deg,#7f53ac,#657ced,#ff6a88); color: #fff; border: none;">Save</button>
                            <a href="{{ route('books.index') }}" class="btn btn-light px-4 rounded-pill shadow border">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
