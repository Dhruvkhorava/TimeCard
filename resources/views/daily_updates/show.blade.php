@extends('layouts.app')

@section('title', 'Log Update Details - Soft UI Dashboard')

@section('content')
<div class="row">
  <div class="col-8 mx-auto">
    <div class="card mb-4">
      <div class="card-header pb-2">
        <h6>Daily Update: {{ \Carbon\Carbon::parse($dailyUpdate->date)->format('d M, Y') }}</h6>
      </div>
      <div class="card-body">
        <div class="mb-4">
            <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary">Task / Project</p>
            <h6 class="text-sm font-weight-bold text-primary">{{ $dailyUpdate->task?->name ?? 'Deleted Task' }}</h6>
            <p class="text-xs text-secondary">Project: {{ $dailyUpdate->task?->project?->name ?? 'N/A' }}</p>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary">Employee</p>
                <p class="text-sm font-weight-bold">{{ $dailyUpdate->employee?->name ?? 'Unknown' }}</p>
            </div>

            <div class="col-3">
                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary">Time Range</p>
                <p class="text-xs font-weight-bold">{{ \Carbon\Carbon::parse($dailyUpdate->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($dailyUpdate->end_time)->format('h:i A') }}</p>
            </div>

            <div class="col-3 text-end">
                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary">Total Hours</p>
                <span class="badge badge-sm bg-gradient-info">{{ number_format($dailyUpdate->hours_spent, 2) }} hours</span>
            </div>
        </div>

        <div class="mb-4">
            <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary">Work Done</p>
            <p class="text-sm p-3 bg-gray-100 border-radius-md">{{ $dailyUpdate->work_done }}</p>
        </div>

        <div class="text-end mt-4">
            <a href="{{ route('daily-updates.index') }}" class="btn btn-outline-primary btn-sm mb-0">Back to List</a>
            @if(auth()->user()->id == $dailyUpdate->employee_id)
            <a href="{{ route('daily-updates.edit', $dailyUpdate->id) }}" class="btn bg-gradient-dark btn-sm mb-0">Edit Log</a>
            @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
