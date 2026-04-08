@extends('layouts.app')

@section('title', 'New Project - Soft UI Dashboard')

@section('content')
<div class="row">
  <div class="col-8 mx-auto">
    <div class="card mb-4">
      <div class="card-header pb-2">
        <h6>Create New Project</h6>
      </div>
      <div class="card-body">
        <form action="{{ route('projects.store') }}" method="POST">
          @csrf
          @hasanyrole('superadmin|admin')
            <div class="mb-3">
              <label for="client_id" class="form-label font-weight-bold text-xs text-uppercase">Client</label>
              <select class="form-control @error('client_id') is-invalid @enderror" id="client_id" name="client_id">
                <option value="">Select a Client</option>
                @foreach($clients as $client)
                  <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                @endforeach
              </select>
              @error('client_id') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
            </div>
          @endhasanyrole
          <div class="mb-3">
            <label for="name" class="form-label font-weight-bold text-xs text-uppercase">Project Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter project name" value="{{ old('name') }}">
            @error('name') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="mb-3">
            <label for="description" class="form-label font-weight-bold text-xs text-uppercase">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Enter project description">{{ old('description') }}</textarea>
            @error('description') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="form-label mb-0 font-weight-bold text-xs text-uppercase">Project Phases</label>
              <button type="button" class="btn btn-outline-primary btn-xs mb-0" id="add-phase">
                <i class="fas fa-plus me-1"></i> Add Phase
              </button>
            </div>
            <div id="phases-container">
              <div class="input-group mb-2 phase-row">
                <input type="text" name="phases[]" class="form-control @error('phases.*') is-invalid @enderror" placeholder="Phase name (e.g. Design, Development...)">
                <button type="button" class="btn btn-outline-danger mb-0 remove-phase" style="display: none;">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
            @error('phases.*') <p class="text-danger text-xs mt-1">Please ensure all phases have names.</p> @enderror
            <small class="text-muted">Add at least one phase for this project.</small>
          </div>

          <div class="text-end mt-4">
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm mb-0">Cancel</a>
            <button type="submit" class="btn bg-gradient-primary btn-sm mb-0">Create Project</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

@endsection
