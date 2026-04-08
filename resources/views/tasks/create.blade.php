@extends('layouts.app')

@section('title', 'Assign Task - Soft UI Dashboard')

@section('content')
    <div class="row">
        <div class="col-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header pb-2">
                    <h6>Assign New Task</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="project_id" class="form-label font-weight-bold text-xs text-uppercase">Project</label>
                            <select class="form-control @error('project_id') is-invalid @enderror" id="project_id" name="project_id">
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}
                                        (Client: {{ $project->client->name ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                            @error('project_id') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="assigned_to" class="form-label font-weight-bold text-xs text-uppercase">Assign To (Multiple)</label>
                            <select class="form-control @error('assigned_to') is-invalid @enderror select2-init" id="assigned_to" name="assigned_to[]" multiple="multiple" data-placeholder="Select Employees">
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ (is_array(old('assigned_to')) && in_array($employee->id, old('assigned_to'))) ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_to') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label font-weight-bold text-xs text-uppercase">Task Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                placeholder="Enter task name" value="{{ old('name') }}">
                            @error('name') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label font-weight-bold text-xs text-uppercase">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Enter task description">{{ old('description') }}</textarea>
                            @error('description') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="text-end">
                            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm mb-0">Cancel</a>
                            <button type="submit" class="btn bg-gradient-primary btn-sm mb-0">Assign Task</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
