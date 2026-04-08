@extends('layouts.app')

@section('title', 'Edit Project - Soft UI Dashboard')

@section('content')
<div class="row">
  <div class="col-8 mx-auto">
    <div class="card mb-4">
      <div class="card-header pb-2">
        <h6>Edit Project: {{ $project->name }}</h6>
      </div>
      <div class="card-body">
        <form action="{{ route('projects.update', $project->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label for="name" class="form-label font-weight-bold text-xs text-uppercase">Project Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $project->name) }}">
            @error('name') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="mb-3">
            <label for="description" class="form-label font-weight-bold text-xs text-uppercase">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $project->description) }}</textarea>
            @error('description') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="mb-3">
            <label for="status" class="form-label font-weight-bold text-xs text-uppercase">Status</label>
            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
              <option value="pending" {{ old('status', $project->status) == 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active</option>
              <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            @error('status') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="text-end">
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm mb-0">Cancel</a>
            <button type="submit" class="btn bg-gradient-primary btn-sm mb-0">Update Project</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection
