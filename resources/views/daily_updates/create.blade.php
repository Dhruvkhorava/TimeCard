@extends('layouts.app')

@section('title', 'Log Daily Work - Soft UI Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header pb-0 text-center">
                <h6 class="mb-0">Submit Daily Update</h6>
                <p class="text-xs text-muted mb-0">Record your progress for multiple tasks at once.</p>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <form action="{{ route('daily-updates.store') }}" method="POST" id="updates-form">
                    @csrf
                    
                    <div class="table-responsive p-0 mt-4">
                        <table class="table align-items-center mb-0" id="updates-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 15%">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" style="width: 25%">Task</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" style="width: 10%">Hours</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" style="width: 30%">Work Done</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" style="width: 20%">Research</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="update-row">
                                    <td class="px-3">
                                        <input type="date" class="form-control form-control-sm @error('updates.*.date') is-invalid @enderror" name="updates[0][date]" value="{{ date('Y-m-d') }}">
                                        @error('updates.*.date') <p class="text-danger text-xxs mt-1">{{ $message }}</p> @enderror
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm @error('updates.*.task_id') is-invalid @enderror" name="updates[0][task_id]">
                                            <option value="">Select Task</option>
                                            @foreach($tasks as $task)
                                                <option value="{{ $task->id }}">
                                                    {{ $task->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('updates.*.task_id') <p class="text-danger text-xxs mt-1">{{ $message }}</p> @enderror
                                    </td>
                                    <td>
                                        <input type="number" step="0.5" class="form-control form-control-sm @error('updates.*.hours_spent') is-invalid @enderror" name="updates[0][hours_spent]" min="0.5" max="24" placeholder="4.5">
                                        @error('updates.*.hours_spent') <p class="text-danger text-xxs mt-1">{{ $message }}</p> @enderror
                                    </td>
                                    <td>
                                        <textarea class="form-control form-control-sm @error('updates.*.work_done') is-invalid @enderror" name="updates[0][work_done]" rows="1" placeholder="Work description..."></textarea>
                                        @error('updates.*.work_done') <p class="text-danger text-xxs mt-1">{{ $message }}</p> @enderror
                                    </td>
                                    <td>
                                        <textarea class="form-control form-control-sm @error('updates.*.research_notes') is-invalid @enderror" name="updates[0][research_notes]" rows="1" placeholder="Research..."></textarea>
                                        @error('updates.*.research_notes') <p class="text-danger text-xxs mt-1">{{ $message }}</p> @enderror
                                    </td>
                                    <td class="text-end px-3">
                                        <button type="button" class="btn btn-link text-danger mb-0 remove-row" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-primary btn-sm mb-0" id="add-row">
                            <i class="fas fa-plus me-1"></i> Add Another Task
                        </button>
                        <div class="text-end">
                            <a href="{{ route('daily-updates.index') }}" class="btn btn-secondary btn-sm mb-0 me-2">Cancel</a>
                            <button type="submit" class="btn bg-gradient-primary btn-sm mb-0">Submit Updates</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
