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
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="project_id" class="form-label font-weight-bold text-xs text-uppercase">Project</label>
                            <select class="form-control select2-init" id="project_id" name="project_id" {{ !auth()->user()->hasAnyRole(['superadmin', 'admin']) ? 'disabled' : '' }}>
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
                            <textarea class="form-control editor @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3"
                                {{ !auth()->user()->hasAnyRole(['superadmin', 'admin']) ? 'disabled' : '' }}>{{ old('description', $task->description) }}</textarea>
                            @error('description') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label font-weight-bold text-xs text-uppercase">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror select2-init" id="status" name="status">
                                <option value="not_started" {{ old('status', $task->status) == 'not_started' ? 'selected' : '' }}>Not
                                    Started</option>
                                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In
                                    Progress</option>
                                <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed
                                </option>
                            </select>
                            @error('status') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-xs text-uppercase">Add New Attachments</label>
                            <div id="attachment-container">
                                <div class="input-group mb-2 attachment-row">
                                    <input type="file" name="attachments[]" class="form-control form-control-sm">
                                    <button type="button" class="btn btn-outline-danger mb-0 remove-attachment" style="display:none;">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" id="add-attachment" class="btn btn-outline-primary btn-xs mt-2 mb-0">
                                <i class="fas fa-plus me-2 text-xs"></i> Add Another File
                            </button>
                            <p class="text-muted text-xxs mt-2">Max 10MB per file</p>
                            @error('attachments.*') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        @if($task->attachments->count() > 0)
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-xs text-uppercase">Current Attachments</label>
                            <ul class="list-group">
                                @foreach($task->attachments as $attachment)
                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                            <i class="ni ni-paper-diploma text-white opacity-10"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark text-sm">{{ $attachment->original_name }}</h6>
                                            <span class="text-xs">{{ number_format($attachment->file_size / 1024, 2) }} KB</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('tasks.attachments.download', $attachment->id) }}" class="btn btn-link text-dark icon-move-right my-auto btn-sm">
                                            <i class="ni ni-cloud-download-95 text-lg" aria-hidden="true"></i>
                                        </a>
                                        <button type="button" class="btn btn-link text-danger my-auto btn-sm" onclick="if(confirm('Are you sure you want to delete this attachment?')) { document.getElementById('delete-attachment-{{ $attachment->id }}').submit(); }">
                                            <i class="ni ni-fat-remove text-lg" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @endif
                        <div class="text-end">
                            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm mb-0">Cancel</a>
                            <button type="submit" class="btn bg-gradient-primary btn-sm mb-0">Update Task</button>
                        </div>
                    </form>

                    @foreach($task->attachments as $attachment)
                    <form id="delete-attachment-{{ $attachment->id }}" action="{{ route('tasks.attachments.destroy', $attachment->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endforeach

      </div>
    </div>
  </div>
</div>
@endsection
