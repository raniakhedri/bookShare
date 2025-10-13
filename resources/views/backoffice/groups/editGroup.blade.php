@extends('backoffice.layouts.user_type.auth')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-400 text-white">
                <div class="card-header bg-transparent text-center py-4 rounded-top border-0">
                    <h3 class="mb-0 font-weight-bold text-white drop-shadow-lg">Edit Group</h3>
                </div>
                <div class="card-body bg-white/80 rounded-bottom">
                    <form action="{{ route('admin.groups.update', $group->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label text-indigo-700">Name</label>
                            <input type="text" class="form-control rounded-pill shadow-sm border-indigo-200 focus:border-pink-400" id="name" name="name" value="{{ old('name', $group->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="theme" class="form-label text-pink-700">Theme</label>
                            <input type="text" class="form-control rounded-pill shadow-sm border-pink-200 focus:border-indigo-400" id="theme" name="theme" value="{{ old('theme', $group->theme) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label text-purple-700">Description</label>
                            <textarea class="form-control rounded shadow-sm border-purple-200 focus:border-pink-400" id="description" name="description" rows="3">{{ old('description', $group->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label text-indigo-700">Image du groupe</label>
                            <input type="file" class="form-control rounded shadow-sm border-indigo-200 focus:border-pink-400" id="image" name="image" accept="image/*">
                            @if($group->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$group->image) }}" alt="Image du groupe" class="img-fluid rounded shadow" style="max-height:120px;">
                                </div>
                            @endif
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="submit" class="btn btn-gradient px-4 rounded-pill shadow" style="background: linear-gradient(90deg,#7f53ac,#657ced,#ff6a88); color: #fff; border: none;">Save</button>
                            <a href="{{ route('admin.groups') }}" class="btn btn-light px-4 rounded-pill shadow border">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
