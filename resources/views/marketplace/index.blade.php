@extends('marketplace.layout')

@section('title', 'Marketplace Dashboard')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-speedometer2"></i> Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('marketplace.books.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> Add New Book
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $myBooksCount }}</h4>
                            <p class="card-text">My Books</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-collection fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $pendingRequests }}</h4>
                            <p class="card-text">Pending Requests</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-clock fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $myPendingRequests }}</h4>
                            <p class="card-text">My Requests</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-arrow-up-right fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $availableBooksCount }}</h4>
                            <p class="card-text">Available Books</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-book fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-lightning"></i> Quick Actions</h5>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('marketplace.books.create') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-plus-circle"></i><br>
                                Add Book
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('marketplace.browse') }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-search"></i><br>
                                Browse Books
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('marketplace.my-books') }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-collection"></i><br>
                                My Books
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('marketplace.received-requests') }}" class="btn btn-outline-warning w-100">
                                <i class="bi bi-inbox"></i><br>
                                Inbox
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history"></i> Recent Transactions
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Book</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Partner</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $transaction)
                                                        <tr>
                                                            <td>
                                                                <strong>{{ $transaction->marketBook->title }}</strong><br>
                                                                <small class="text-muted">by {{ $transaction->marketBook->author }}</small>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-{{ $transaction->type === 'gift' ? 'success' : 'primary' }}">
                                                                    <i
                                                                        class="bi bi-{{ $transaction->type === 'gift' ? 'gift' : 'arrow-left-right' }}"></i>
                                                                    {{ ucfirst($transaction->type) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-{{ 
                                                                            $transaction->status === 'pending' ? 'warning' :
                                        ($transaction->status === 'accepted' ? 'success' :
                                            ($transaction->status === 'rejected' ? 'danger' : 'info'))
                                                                        }}">
                                                                    {{ ucfirst($transaction->status) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                @if($transaction->requester_id === auth()->id())
                                                                    {{ $transaction->marketBook->owner->name }}
                                                                    <small class="text-muted">(Owner)</small>
                                                                @else
                                                                    {{ $transaction->requester->name }}
                                                                    <small class="text-muted">(Requester)</small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <small>{{ $transaction->created_at->format('M d, Y') }}</small>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('marketplace.transactions.show', $transaction) }}"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    <i class="bi bi-eye"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No recent transactions</p>
                            <a href="{{ route('marketplace.browse') }}" class="btn btn-primary">
                                Browse Books to Get Started
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection