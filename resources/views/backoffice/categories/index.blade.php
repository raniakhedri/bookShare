@extends('backoffice.layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-1 fw-bold text-dark">🏷️ Category Management</h5>
            <form action="{{ route('categories.store') }}" method="POST" class="row g-2 align-items-center mb-0" style="min-width:320px;">
              @csrf
             
              <div class="col-auto flex-grow-1">
                <input type="text" class="form-control form-control-sm rounded shadow border-0" id="categoryNameInput" name="name" placeholder="Category name" required style="min-width:160px;">
              </div>
              <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center gap-2 px-3"><span style="font-size:1.1em;">➕</span> Add</button>
              </div>
            </form>
                    </div>
                </div>
                <div class="card-body bg-light rounded-bottom">
                    <div class="table-responsive">
                        <table class="table table-hover align-items-center mb-0" id="categoriesTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 text-uppercase text-muted text-xs fw-bold">ID</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold">Name</th>
                                    <th class="text-uppercase text-muted text-xs fw-bold text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr class="border-bottom">
                                    <td class="fw-bold ps-4">{{ $category->id }}</td>
                                    <td id="cat-name-{{ $category->id }}">{{ $category->name }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded me-1" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}" title="Edit">
                                            <span style="font-size:1.1em;">✏️</span>
                                        </button>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded" onclick="return confirm('Delete this category?')" title="Delete">
                                                <span style="font-size:1.1em;">🗑️</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <!-- Edit category modal -->
                                <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="editCategoryLabel{{ $category->id }}" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-3 shadow-lg border-0">
                                      <div class="modal-header bg-primary text-white rounded-top">
                                        <h5 class="modal-title fw-bold" id="editCategoryLabel{{ $category->id }}">
                                          <span style="font-size:1.1em;">✏️</span> Edit category
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <form action="{{ route('categories.update', $category->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body bg-light">
                                          <div class="mb-3">
                                            <label for="name-{{ $category->id }}" class="form-label text-primary">Category name</label>
                                            <input type="text" class="form-control rounded-pill shadow-sm border-primary" id="name-{{ $category->id }}" name="name" value="{{ $category->name }}" required>
                                          </div>
                                        </div>
                                        <div class="modal-footer bg-white rounded-bottom border-0">
                                          <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                          <button type="submit" class="btn btn-primary rounded-pill px-4">Save</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.card {
  border-radius: 12px;
}
.table th {
  font-weight: 600;
  font-size: 0.75rem;
  padding: 1rem 0.75rem;
  border-bottom: 2px solid #e9ecef;
}
.table td {
  padding: 1rem 0.75rem;
  vertical-align: middle;
  border-bottom: 1px solid #e9ecef;
}
.badge {
  font-size: 0.75rem;
  padding: 0.4rem 0.7rem;
  font-weight: 500;
}
.btn-sm {
  padding: 0.35rem 0.75rem;
  font-size: 0.8rem;
  min-width: 36px;
  height: 36px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
.btn-sm span {
  font-size: 0.8rem;
  width: 16px;
  height: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.bg-light {
  background-color: #f8f9fa !important;
}
.text-muted {
  color: #6c757d !important;
}
.table-hover tbody tr:hover {
  background-color: #f8f9fa;
}
.btn:hover {
  transform: translateY(-1px);
  transition: all 0.2s ease;
}
.text-dark {
  color: #212529 !important;
}
.border {
  border-color: #dee2e6 !important;
}
.d-flex.align-items-center span {
  line-height: 1;
}
@media (max-width: 768px) {
  .card-header .d-flex {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }
  .table-responsive {
    font-size: 0.8rem;
  }
  .btn-sm {
    min-width: 32px;
    height: 32px;
    padding: 0.25rem 0.5rem;
  }
  .table th, .table td {
    padding: 0.75rem 0.5rem;
  }
  .badge {
    font-size: 0.7rem;
    padding: 0.3rem 0.5rem;
  }
  .btn-sm span {
    font-size: 0.7rem;
    width: 14px;
    height: 14px;
  }
}
</style>
@endsection
