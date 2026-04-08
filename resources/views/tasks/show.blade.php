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
            <p class="text-sm">{{ $task->description ?: 'No description provided.' }}</p>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary">Assigned To</p>
                <p class="text-sm font-weight-bold">{{ $task->employee->name }}</p>
            </div>
            <div class="col-6">
                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary">Status</p>
                <span class="badge badge-sm bg-gradient-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'info' : 'warning') }}">
                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                </span>
            </div>
        </div>

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
