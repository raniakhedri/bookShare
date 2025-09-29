@extends('marketplace.layout')

@section('title', 'My Books')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-collection"></i> My Books</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('marketplace.books.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Book
            </a>
        </div>
    </div>

    @if($books->count() > 0)
        <div class="row">
            @foreach($books as $book)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card book-card h-100">
                        <div class="position-relative">
                            @if($book->image)
                                <img src="{{ Storage::url($book->image) }}" class="card-img-top book-image" alt="{{ $book->title }}">
                            @else
                                <div class="card-img-top book-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="bi bi-book fs-1 text-muted"></i>
                                </div>
                            @endif

                            <span class="badge bg-{{ 
                                        $book->condition === 'New' ? 'success' :
                        ($book->condition === 'Good' ? 'primary' :
                            ($book->condition === 'Fair' ? 'warning' : 'secondary'))
                                    }} status-badge">
                                {{ $book->condition }}
                            </span>

                            <!-- Availability Badge -->
                            <span
                                class="badge bg-{{ $book->is_available ? 'success' : 'secondary' }} position-absolute bottom-0 end-0 m-2">
                                <i class="bi bi-{{ $book->is_available ? 'check-circle' : 'x-circle' }}"></i>
                                {{ $book->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $book->title }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">by {{ $book->author }}</h6>

                            <p class="card-text">
                                {{ Str::limit($book->description, 80) }}
                            </p>

                            <!-- Request Counter -->
                            @if($book->pending_requests_count > 0)
                                <div class="alert alert-warning py-2">
                                    <i class="bi bi-bell"></i>
                                    {{ $book->pending_requests_count }} pending
                                    {{ Str::plural('request', $book->pending_requests_count) }}
                                </div>
                            @endif

                            <div class="mt-auto">
                                @if($book->price)
                                    <div class="text-center mb-2">
                                        <span class="badge bg-info">${{ number_format($book->price, 2) }}</span>
                                    </div>
                                @endif

                                <div class="d-grid gap-2">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('marketplace.books.show', $book) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('marketplace.books.edit', $book) }}"
                                            class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('marketplace.books.toggle-availability', $book) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-outline-{{ $book->is_available ? 'warning' : 'success' }} btn-sm">
                                                <i class="bi bi-{{ $book->is_available ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('marketplace.books.destroy', $book) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this book?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12">
                {{ $books->links() }}
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-collection fs-1 text-muted"></i>
                        <h4 class="mt-3">You haven't added any books yet</h4>
                        <p class="text-muted">Start building your marketplace collection by adding books you'd like to share or
                            exchange.</p>
                        <a href="{{ route('marketplace.books.create') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle"></i> Add Your First Book
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection