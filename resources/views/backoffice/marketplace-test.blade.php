@extends('backoffice.layouts.app')

@section('title', 'Marketplace Management')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6>Admin Marketplace - TEST VERSION</h6>
                    </div>
                    <div class="card-body">
                        <p>Total Books: {{ $totalBooks ?? 'N/A' }}</p>
                        <p>Available Books: {{ $availableBooks ?? 'N/A' }}</p>
                        <p>Total Transactions: {{ $totalTransactions ?? 'N/A' }}</p>
                        <p>Market Books Count: {{ $marketBooks->count() ?? 'N/A' }}</p>
                        
                        @if($marketBooks && $marketBooks->count() > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Owner</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($marketBooks as $book)
                                            <tr>
                                                <td>{{ $book->title }}</td>
                                                <td>{{ $book->author }}</td>
                                                <td>{{ $book->owner->name ?? 'Unknown' }}</td>
                                                <td>{{ $book->is_available ? 'Available' : 'Unavailable' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>No market books found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection