@extends('backoffice.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h1>Test - Admin Marketplace</h1>
                    <p>If you can see this, the layout is working!</p>
                    <p>Total MarketBooks: {{ $totalMarketBooks }}</p>
                    <p>Available: {{ $availableMarketBooks }}</p>
                    <p>Transactions: {{ $totalTransactions }}</p>
                    <p>Pending: {{ $pendingTransactions }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total MarketBooks</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ $totalMarketBooks }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                    <i class="ni ni-shop text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Available Books</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ $availableMarketBooks }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Transactions</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ $totalTransactions }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i class="ni ni-delivery-fast text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Pending Requests</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ $pendingTransactions }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                    <i class="ni ni-time-alarm text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MarketBooks Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>📚 MarketBooks Overview</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if($marketBooks->count() > 0)
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Book</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Owner</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Condition</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($marketBooks as $book)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            @if($book->image_path || $book->image)
                                                                <img src="{{ asset('storage/' . ($book->image_path ?: $book->image)) }}" 
                                                                     class="avatar avatar-sm me-3" alt="Book cover">
                                                            @else
                                                                <div class="avatar avatar-sm bg-gradient-secondary me-3 d-flex align-items-center justify-content-center">
                                                                    <i class="ni ni-books text-white"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $book->title }}</h6>
                                                            <p class="text-xs text-secondary mb-0">by {{ $book->author }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            <div class="avatar avatar-sm bg-gradient-info me-3 d-flex align-items-center justify-content-center">
                                                                <i class="ni ni-single-02 text-white"></i>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $book->owner->name }}</h6>
                                                            <p class="text-xs text-secondary mb-0">{{ $book->owner->email }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    @if($book->is_available)
                                                        <span class="badge badge-sm bg-gradient-success">Available</span>
                                                    @else
                                                        <span class="badge badge-sm bg-gradient-secondary">Unavailable</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-xs font-weight-bold">
                                                        {{ ucfirst($book->condition) }}
                                                    </span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-xs font-weight-bold">
                                                        {{ $book->created_at->format('M d, Y') }}
                                                    </span>
                                                </td>
                                                <td class="align-middle">
                                                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs" 
                                                       data-toggle="tooltip" data-original-title="View book">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="ni ni-shop text-muted" style="font-size: 4rem;"></i>
                                    <h5 class="text-muted mt-3">No MarketBooks Yet</h5>
                                    <p class="text-sm text-muted">There are no marketplace books in the system.</p>
                                    <p class="text-sm text-muted">Users will add books when they start sharing!</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>🚀 Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 mb-2">
                                <a href="{{ route('admin.dashboard') }}" class="btn bg-gradient-primary btn-sm w-100">
                                    <i class="ni ni-tv-2 me-1"></i>
                                    View Dashboard
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-6 mb-2">
                                <a href="{{ route('marketplace') }}" class="btn bg-gradient-info btn-sm w-100" target="_blank">
                                    <i class="ni ni-shop me-1"></i>
                                    View Marketplace
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-6 mb-2">
                                <a href="{{ route('admin.user-management') }}" class="btn bg-gradient-success btn-sm w-100">
                                    <i class="ni ni-single-02 me-1"></i>
                                    Manage Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection