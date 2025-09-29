@extends('backoffice.layouts.app')

@section('title', 'Marketplace Management')

@section('content')
    <div class="container-fluid py-4">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Books</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $totalBooks }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                    <i class="ni ni-books text-lg opacity-10" aria-hidden="true"></i>
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
                                    <h5 class="font-weight-bolder mb-0">{{ $availableBooks }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Requests</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $totalTransactions }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
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
                                    <h5 class="font-weight-bolder mb-0">{{ $pendingTransactions }}</h5>
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

        <!-- Market Books Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-1">Market Books Management</h6>
                                <p class="text-sm mb-0 text-secondary">Monitor and manage all books in the marketplace</p>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <div class="input-group input-group-outline" style="width: 250px;">
                                    <input type="text" class="form-control" placeholder="Search books..." id="searchBooks">
                                </div>
                                <select class="form-select" id="filterStatus" style="width: 150px;">
                                    <option value="">All Status</option>
                                    <option value="available">Available</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                                <select class="form-select" id="filterCondition" style="width: 150px;">
                                    <option value="">All Conditions</option>
                                    <option value="new">New</option>
                                    <option value="excellent">Excellent</option>
                                    <option value="good">Good</option>
                                    <option value="fair">Fair</option>
                                    <option value="poor">Poor</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="badge badge-sm bg-gradient-success me-2">Available:
                                    {{ $availableBooks }}</span>
                                <span class="badge badge-sm bg-gradient-secondary">Unavailable:
                                    {{ $totalBooks - $availableBooks }}</span>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">Total: {{ $totalBooks }} books</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Book
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Owner</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Status</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Condition</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Requests</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Created</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($marketBooks as $book)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="me-3">
                                                        @if($book->image_path)
                                                            <img src="{{ asset('storage/' . $book->image_path) }}"
                                                                class="avatar avatar-sm me-3 border-radius-lg"
                                                                alt="{{ $book->title }}">
                                                        @else
                                                            <div
                                                                class="avatar avatar-sm me-3 border-radius-lg bg-gradient-secondary d-flex align-items-center justify-content-center">
                                                                <i class="ni ni-books text-white text-sm"></i>
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
                                                <p class="text-xs font-weight-bold mb-0">{{ $book->owner->name }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $book->owner->email }}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                @if($book->is_available)
                                                    <span class="badge badge-sm bg-gradient-success">Available</span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-secondary">Unavailable</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span
                                                    class="badge badge-sm bg-gradient-info">{{ ucfirst($book->condition) }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <span
                                                        class="text-secondary text-xs font-weight-bold">{{ $book->requests_count }}</span>
                                                    @if($book->pending_requests_count > 0)
                                                        <span
                                                            class="badge badge-sm bg-gradient-warning">{{ $book->pending_requests_count }}
                                                            pending</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $book->created_at->format('M d, Y') }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{ route('marketplace.books.show', $book->id) }}" 
                                                       class="btn btn-link text-primary text-gradient px-2 mb-0" 
                                                       title="View Book" target="_blank">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-link text-info text-gradient px-2 mb-0"
                                                            title="Book Details"
                                                            onclick="showBookDetails({{ $book->id }}, '{{ $book->title }}', '{{ $book->author }}', '{{ $book->description }}', '{{ $book->condition }}', '{{ $book->owner->name }}', '{{ $book->owner->email }}', '{{ $book->created_at->format('M d, Y H:i') }}')">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-link text-danger text-gradient px-2 mb-0"
                                                            title="Delete Book"
                                                            onclick="confirmDelete({{ $book->id }}, '{{ $book->title }}')">
                                                        <i class="far fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                                
                                                <!-- Hidden form for deletion -->
                                                <form id="delete-form-{{ $book->id }}" 
                                                      action="{{ route('admin.marketplace.book.delete', $book->id) }}"
                                                      method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="ni ni-books text-secondary" style="font-size: 3rem;"></i>
                                                    <h6 class="text-secondary mt-2">No market books found</h6>
                                                    <p class="text-xs text-secondary">Users haven't shared any books yet</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Book Details Modal -->
    <div class="modal fade" id="bookDetailsModal" tabindex="-1" aria-labelledby="bookDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary">
                    <h5 class="modal-title text-white" id="bookDetailsModalLabel">Book Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Book Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-sm font-weight-bold">Title:</td>
                                    <td class="text-sm" id="modal-title">-</td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-bold">Author:</td>
                                    <td class="text-sm" id="modal-author">-</td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-bold">Condition:</td>
                                    <td class="text-sm" id="modal-condition">-</td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-bold">Created:</td>
                                    <td class="text-sm" id="modal-created">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Owner Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-sm font-weight-bold">Name:</td>
                                    <td class="text-sm" id="modal-owner-name">-</td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-bold">Email:</td>
                                    <td class="text-sm" id="modal-owner-email">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-primary">Description</h6>
                            <p class="text-sm" id="modal-description">-</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="view-book-btn">View in Marketplace</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-gradient-danger">
                    <h5 class="modal-title text-white" id="deleteConfirmModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                        <h6 class="mt-3">Delete Market Book</h6>
                        <p class="text-sm">Are you sure you want to delete "<span id="delete-book-title" class="font-weight-bold"></span>"?</p>
                        <p class="text-xs text-secondary">This action cannot be undone. All associated requests and transactions will also be deleted.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete-btn">
                        <i class="fas fa-trash-alt me-1"></i> Delete Book
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchBooks').addEventListener('input', function() {
            filterTable();
        });

        document.getElementById('filterStatus').addEventListener('change', function() {
            filterTable();
        });

        document.getElementById('filterCondition').addEventListener('change', function() {
            filterTable();
        });

        function filterTable() {
            const searchTerm = document.getElementById('searchBooks').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const conditionFilter = document.getElementById('filterCondition').value;
            
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                if (row.querySelector('td[colspan="7"]')) return; // Skip empty state row
                
                const title = row.cells[0].textContent.toLowerCase();
                const owner = row.cells[1].textContent.toLowerCase();
                const status = row.cells[2].textContent.toLowerCase();
                const condition = row.cells[3].textContent.toLowerCase();
                
                let show = true;
                
                // Search filter
                if (searchTerm && !title.includes(searchTerm) && !owner.includes(searchTerm)) {
                    show = false;
                }
                
                // Status filter
                if (statusFilter && !status.includes(statusFilter)) {
                    show = false;
                }
                
                // Condition filter
                if (conditionFilter && !condition.includes(conditionFilter)) {
                    show = false;
                }
                
                row.style.display = show ? '' : 'none';
            });
        }

        function showBookDetails(id, title, author, description, condition, ownerName, ownerEmail, created) {
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-author').textContent = author;
            document.getElementById('modal-description').textContent = description || 'No description available';
            document.getElementById('modal-condition').textContent = condition.charAt(0).toUpperCase() + condition.slice(1);
            document.getElementById('modal-owner-name').textContent = ownerName;
            document.getElementById('modal-owner-email').textContent = ownerEmail;
            document.getElementById('modal-created').textContent = created;
            
            document.getElementById('view-book-btn').onclick = function() {
                window.open(`{{ route('marketplace.books.show', '') }}/${id}`, '_blank');
            };
            
            const modal = new bootstrap.Modal(document.getElementById('bookDetailsModal'));
            modal.show();
        }

        let deleteBookId = null;

        function confirmDelete(id, title) {
            deleteBookId = id;
            document.getElementById('delete-book-title').textContent = title;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            modal.show();
        }

        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            if (deleteBookId) {
                document.getElementById(`delete-form-${deleteBookId}`).submit();
            }
        });
    </script>
@endsection