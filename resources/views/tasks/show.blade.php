@extends('layouts.app')

@section('title', 'Task Details - Soft UI Dashboard')

@section('content')
<div class="row">
  <div class="col-8 mx-auto">
    <div class="card mb-4">
      <div class="card-header pb-2">
        <div class="row">
          <div class="col-lg-6 col-7">
            <h6>Task: {{ $task->name }}</h6>
          </div>
          <div class="col-lg-6 col-5 my-auto text-end">
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary btn-sm mb-0">Back to List</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="mb-4">
            <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary">Project</p>
            <h6 class="text-sm font-weight-bold text-primary">{{ $task->project->name }}</h6>
        </div>

        <div class="mb-4">
            <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary">Description</p>
            <div class="text-sm task-description">{!! $task->description ?: 'No description provided.' !!}</div>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary">Assigned To</p>
                <p class="text-sm font-weight-bold">{{ $task->employee->name ?? 'N/A' }}</p>
            </div>
            <div class="col-6">
                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary">Status</p>
                <span class="badge badge-sm bg-gradient-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'info' : 'warning') }}">
                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                </span>
            </div>
        </div>

        @if($task->attachments->count() > 0)
        <div class="mb-4">
            <p class="text-xs font-weight-bold mb-2 text-uppercase text-secondary">Attachments</p>
            <div class="row">
                @foreach($task->attachments as $attachment)
                    @php
                        $extension = strtolower(pathinfo($attachment->original_name, PATHINFO_EXTENSION));
                        $iconClass = 'ni ni-folder-17';
                        $colorClass = 'bg-gradient-primary';
                        
                        if ($extension === 'pdf') {
                            $iconClass = 'fas fa-file-pdf';
                            $colorClass = 'bg-gradient-danger';
                        } elseif (in_array($extension, ['xls', 'xlsx', 'csv'])) {
                            $iconClass = 'fas fa-file-excel';
                            $colorClass = 'bg-gradient-success';
                        }
                    @endphp
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center p-2 border border-radius-lg border-light shadow-none">
                            <div class="icon icon-sm icon-shape {{ $colorClass }} shadow text-center border-radius-md me-3">
                                <i class="{{ $iconClass }} text-white opacity-10"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <h6 class="mb-0 text-dark text-sm text-truncate">{{ $attachment->original_name }}</h6>
                                <p class="text-xxs text-secondary mb-0">{{ number_format($attachment->file_size / 1024, 2) }} KB</p>
                            </div>
                            <div class="ms-3 d-flex">
                                <a href="{{ route('tasks.attachments.view', $attachment->id) }}" target="_blank" class="btn btn-link text-info icon-move-right my-auto btn-sm p-0 me-2" data-bs-toggle="tooltip" title="View Document">
                                    <i class="fas fa-eye text-lg"></i>
                                </a>
                                <a href="{{ route('tasks.attachments.download', $attachment->id) }}" class="btn btn-link text-primary icon-move-right my-auto btn-sm p-0" data-bs-toggle="tooltip" title="Download">
                                    <i class="ni ni-cloud-download-95 text-lg"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <hr class="horizontal dark">

        <h6>Recent Progress Updates</h6>
        <div class="timeline timeline-one-side mt-3" id="task-updates">
          @forelse($task->dailyUpdates()->latest()->get() as $update)
          <div class="timeline-block mb-3">
            <span class="timeline-step">
              <i class="ni ni-bell-55 text-info text-gradient"></i>
            </span>
            <div class="timeline-content">
              <h6 class="text-dark text-sm font-weight-bold mb-0">{{ $update->work_done }}</h6>
              <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ \Carbon\Carbon::parse($update->date)->format('d M, Y') }} - {{ $update->hours_spent }} hours</p>
              @if($update->research_notes)
              <p class="text-xs text-muted mb-0 mt-1"><strong>Research:</strong> {{ $update->research_notes }}</p>
              @endif
            </div>
          </div>
          @empty
          <p class="text-xs text-secondary text-center py-3">No progress updates yet.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
