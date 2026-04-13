@extends('layouts.app')

@section('title', 'Edit Daily Update - Soft UI Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto mt-4">
        <div class="card blur shadow-blur border-0">
            <div class="card-header pb-0 bg-transparent text-center">
                <div class="avatar avatar-lg bg-gradient-info shadow-info text-center border-radius-lg mb-3">
                    <i class="fas fa-edit text-white"></i>
                </div>
                <h6 class="font-weight-bolder">Edit Daily Log</h6>
                <p class="text-sm mb-0">Update your project progress and hours.</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('daily-updates.update', $dailyUpdate->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="task_id" class="form-control-label font-weight-bold text-xs text-uppercase">Project Task</label>
                                <select class="form-control" id="task_id" name="task_id" disabled>
                                    <option value="{{ $dailyUpdate->task_id }}">{{ $dailyUpdate->task?->name ?? 'Deleted Task' }} ({{ $dailyUpdate->task?->project?->name ?? 'N/A' }})</option>
                                </select>

                                <small class="text-xs text-muted mt-2 d-block">Task cannot be changed after submission.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date" class="form-control-label font-weight-bold text-xs text-uppercase">Work Date</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', $dailyUpdate->date) }}">
                                @error('date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_time" class="form-control-label font-weight-bold text-xs text-uppercase">Start Time</label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($dailyUpdate->start_time)->format('H:i')) }}" step="60">
                                @error('start_time') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_time" class="form-control-label font-weight-bold text-xs text-uppercase">End Time</label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($dailyUpdate->end_time)->format('H:i')) }}" step="60">
                                @error('end_time') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="work_done" class="form-control-label font-weight-bold text-xs text-uppercase">Detailed Progress (Work Done)</label>
                        <textarea class="form-control @error('work_done') is-invalid @enderror" id="work_done" name="work_done" rows="4" placeholder="What milestones did you hit today?">{{ old('work_done', $dailyUpdate->work_done) }}</textarea>
                        @error('work_done') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                    </div>


                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('daily-updates.index') }}" class="btn btn-light btn-sm mb-0 me-2">Go Back</a>
                        <button type="submit" class="btn bg-gradient-info btn-sm mb-0 px-4">Update Log Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
