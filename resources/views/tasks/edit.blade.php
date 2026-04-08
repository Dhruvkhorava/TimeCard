@extends('layouts.app')

@section('title', 'Edit Task - Soft UI Dashboard')

@section('content')
<div class="row">
  <div class="col-8 mx-auto">
    <div class="card mb-4">
      <div class="card-header pb-2">
        <h6>Edit Task: {{ $task->name }}</h6>
      </div>
      <div class="card-body">
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="project_id" class="form-label font-weight-bold text-xs text-uppercase">Project</label>
                            <select class="form-control" id="project_id" name="project_id" {{ !auth()->user()->hasAnyRole(['superadmin', 'admin']) ? 'disabled' : '' }}>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="assigned_to" class="form-label font-weight-bold text-xs text-uppercase">Assigned To (Multiple)</label>
                            <select class="form-control select2-init" id="assigned_to" name="assigned_to[]" multiple="multiple" 
                                data-placeholder="Select Employees"
                                {{ !auth()->user()->hasAnyRole(['superadmin', 'admin']) ? 'disabled' : '' }}>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ (is_array(old('assigned_to', $task->employees->pluck('id')->toArray())) && in_array($employee->id, old('assigned_to', $task->employees->pluck('id')->toArray()))) ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_to') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label font-weight-bold text-xs text-uppercase">Task Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                value="{{ old('name', $task->name) }}"
                                {{ !auth()->user()->hasAnyRole(['superadmin', 'admin']) ? 'disabled' : '' }}>
                            @error('name') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label font-weight-bold text-xs text-uppercase">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3"
                                {{ !auth()->user()->hasAnyRole(['superadmin', 'admin']) ? 'disabled' : '' }}>{{ old('description', $task->description) }}</textarea>
                            @error('description') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label font-weight-bold text-xs text-uppercase">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="not_started" {{ old('status', $task->status) == 'not_started' ? 'selected' : '' }}>Not
                                    Started</option>
                                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In
                                    Progress</option>
                                <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed
                                </option>
                            </select>
                            @error('status') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="text-end">
                            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm mb-0">Cancel</a>
                            <button type="submit" class="btn bg-gradient-primary btn-sm mb-0">Update Task</button>
                        </div>
                    </form>

      </div>
    </div>
  </div>
</div>
@endsection
