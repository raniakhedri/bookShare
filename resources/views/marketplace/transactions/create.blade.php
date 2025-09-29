@extends('marketplace.layout')

@section('title', 'Request Book')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-arrow-right-circle"></i> Request Book</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('marketplace.browse') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Browse
        </a>
    </div>
</div>

<div class="row">
    <!-- Book Details -->
    <div class="col-md-4">
        <div class="card">
            <div class="position-relative">
                @if($book->image)
                    <img src="{{ Storage::url($book->image) }}" class="card-img-top" style="height: 300px; object-fit: cover;" alt="{{ $book->title }}">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                        <i class="bi bi-book fs-1 text-muted"></i>
                    </div>
                @endif
                
                <span class="badge bg-{{ 
                    $book->condition === 'New' ? 'success' : 
                    ($book->condition === 'Good' ? 'primary' : 
                    ($book->condition === 'Fair' ? 'warning' : 'secondary'))
                }} position-absolute top-0 end-0 m-2">
                    {{ $book->condition }}
                </span>
            </div>
            
            <div class="card-body">
                <h5 class="card-title">{{ $book->title }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">by {{ $book->author }}</h6>
                
                <p class="card-text">{{ $book->description }}</p>
                
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-person"></i> {{ $book->owner->name }}
                    </small>
                    @if($book->price)
                        <span class="badge bg-info">${{ number_format($book->price, 2) }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Request Form -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-envelope"></i> Make a Request
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('marketplace.transactions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="marketbook_id" value="{{ $book->id }}">
                    
                    <!-- Request Type -->
                    <div class="mb-4">
                        <label class="form-label">Request Type <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" 
                                                   id="gift" value="gift" {{ old('type') === 'gift' ? 'checked' : '' }} required>
                                            <label class="form-check-label w-100" for="gift">
                                                <i class="bi bi-gift fs-1 text-success d-block mb-2"></i>
                                                <h5>Request as Gift</h5>
                                                <p class="text-muted small">Ask the owner to give you the book</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" 
                                                   id="exchange" value="exchange" {{ old('type') === 'exchange' ? 'checked' : '' }} required>
                                            <label class="form-check-label w-100" for="exchange">
                                                <i class="bi bi-arrow-left-right fs-1 text-primary d-block mb-2"></i>
                                                <h5>Propose Exchange</h5>
                                                <p class="text-muted small">Offer one of your books in exchange</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('type')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Exchange Book Selection (Hidden by default) -->
                    <div id="exchange-section" style="display: none;" class="mb-4">
                        <label for="offered_marketbook_id" class="form-label">Select Book to Offer <span class="text-danger">*</span></label>
                        <select class="form-select @error('offered_marketbook_id') is-invalid @enderror" 
                                id="offered_marketbook_id" name="offered_marketbook_id">
                            <option value="">Choose a book from your collection</option>
                            @foreach($userBooks as $userBook)
                                <option value="{{ $userBook->id }}" 
                                        {{ old('offered_marketbook_id') == $userBook->id ? 'selected' : '' }}
                                        data-condition="{{ $userBook->condition }}"
                                        data-price="{{ $userBook->price }}">
                                    {{ $userBook->title }} by {{ $userBook->author }} ({{ $userBook->condition }})
                                </option>
                            @endforeach
                        </select>
                        @error('offered_marketbook_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        @if($userBooks->count() === 0)
                            <div class="alert alert-info mt-2">
                                <i class="bi bi-info-circle"></i>
                                You don't have any available books to offer for exchange. 
                                <a href="{{ route('marketplace.books.create') }}">Add some books</a> first.
                            </div>
                        @endif
                    </div>
                    
                    <!-- Message -->
                    <div class="mb-3">
                        <label for="message" class="form-label">Message to Owner</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" 
                                  id="message" name="message" rows="4" 
                                  placeholder="Write a friendly message to the book owner...">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Exchange Notes (Hidden by default) -->
                    <div id="exchange-notes-section" style="display: none;" class="mb-3">
                        <label for="exchange_notes" class="form-label">Exchange Notes</label>
                        <textarea class="form-control @error('exchange_notes') is-invalid @enderror" 
                                  id="exchange_notes" name="exchange_notes" rows="3" 
                                  placeholder="Additional notes about the exchange...">{{ old('exchange_notes') }}</textarea>
                        @error('exchange_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('marketplace.browse') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Send Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const giftRadio = document.getElementById('gift');
    const exchangeRadio = document.getElementById('exchange');
    const exchangeSection = document.getElementById('exchange-section');
    const exchangeNotesSection = document.getElementById('exchange-notes-section');
    const offeredBookSelect = document.getElementById('offered_marketbook_id');
    
    function toggleSections() {
        if (exchangeRadio.checked) {
            exchangeSection.style.display = 'block';
            exchangeNotesSection.style.display = 'block';
            offeredBookSelect.required = true;
        } else {
            exchangeSection.style.display = 'none';
            exchangeNotesSection.style.display = 'none';
            offeredBookSelect.required = false;
            offeredBookSelect.value = '';
        }
    }
    
    giftRadio.addEventListener('change', toggleSections);
    exchangeRadio.addEventListener('change', toggleSections);
    
    // Initialize on page load
    toggleSections();
    
    // Check if exchange was previously selected
    if (exchangeRadio.checked) {
        toggleSections();
    }
});
</script>
@endsection