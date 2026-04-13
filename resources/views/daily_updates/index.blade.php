@extends('layouts.app')

@section('title', 'Daily Updates - Soft UI Dashboard')

@section('content')
    @if (auth()->user()->hasRole('employee'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <form action="{{ route('daily-updates.store') }}" method="POST" id="updates-form"
                          data-fetch-by-date-url="{{ route('daily-updates.fetch-by-date') }}"
                          data-fetch-tasks-url="{{ url('daily-updates/fetch-tasks') }}"
                          data-projects="{{ $projects->map(fn($p) => ['id' => $p->id, 'name' => $p->name])->toJson() }}">
                        @csrf
                        <div class="card-header pb-0 border-0 bg-transparent">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('tasks.index') }}" class="btn btn-link text-primary p-0 mb-0 me-3" data-bs-toggle="tooltip" title="Back to Task List">
                                        <i class="fas fa-chevron-left text-lg"></i>
                                    </a>
                                    <h6 class="mb-0">Submit Daily Update</h6>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <label class="text-xs font-weight-bold mb-0 text-uppercase text-secondary">Entry Date:</label>
                                    <input type="date" class="form-control form-control-sm w-auto" name="date" required
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                                <table class="table align-items-center mb-0" id="updates-table">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 max-width-200">Project</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 max-width-200">Task</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 max-width-100">Start Time</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 max-width-100">End Time</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 max-width-300">Work Done</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="update-row">
                                            <td>
                                                <select class="form-control form-control-sm project-select" name="updates[0][project_id]"
                                                    required>
                                                    <option value="">Select Project</option>
                                                    @foreach ($projects as $project)
                                                        <option value="{{ $project->id }}">
                                                            {{ $project->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="hidden" name="updates[0][id]" value="">
                                                <select class="form-control form-control-sm task-select" name="updates[0][task_id]"
                                                    required disabled>
                                                    <option value="">Select Project First</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="time" class="form-control form-control-sm"
                                                    name="updates[0][start_time]" step="60" required>
                                            </td>
                                            <td>
                                                <input type="time" class="form-control form-control-sm"
                                                    name="updates[0][end_time]" step="60" required>
                                            </td>
                                            <td>
                                                <textarea class="form-control form-control-sm" name="updates[0][work_done]" rows="4" required
                                                    placeholder="Work description..."></textarea>
                                            </td>
                                            <td class="text-end px-3">
                                                <button type="button" class="btn btn-link text-danger mb-0 remove-row"
                                                    style="display: none;">
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
                                    <button type="submit" class="btn bg-dark text-white btn-sm mb-0">Submit Updates</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-2">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0">Daily Work Updates History</h6>
                        <div class="bg-gray-100 border-radius-lg px-3 py-2 d-flex align-items-center shadow-none">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <span class="text-xs font-weight-bold text-uppercase text-secondary me-2">Total Filtered:</span>
                            <span class="badge bg-gradient-dark mb-0">{{ number_format($total_hours, 2) }} HRS</span>
                        </div>
                    </div>
                    <form action="{{ route('daily-updates.index') }}" method="GET"
                        class="d-flex align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <label class="text-xxs font-weight-bold text-uppercase text-secondary mb-0">From:</label>
                            <input type="date" name="from_date" class="form-control form-control-sm w-auto"
                                value="{{ $from_date }}">
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label class="text-xxs font-weight-bold text-uppercase text-secondary mb-0">To:</label>
                            <input type="date" name="to_date" class="form-control form-control-sm w-auto"
                                value="{{ $to_date }}">
                        </div>
                        <button type="submit" class="btn bg-gradient-primary btn-sm mb-0">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </form>
                </div>
                <div class="card-body px-4 pt-2 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-4 datatable-init" id="history-table" 
                               data-order-index="0" data-order-dir="desc">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Task / Project</th>
                                    @unless (auth()->user()->hasRole('employee'))
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Employee</th>
                                    @endunless
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Hours</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" style="width: 35%">
                                        Work Done</th>
                                    <th class="text-secondary opacity-7" style="width: 1%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($updates as $update)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($update->date)->format('d M, Y') }}</td>
                                        <td style="width: 25%">
                                            <h6 class="mb-0 text-sm small text-dark font-weight-bold">
                                                {{ $update->task?->name ?? 'Deleted Task' }}</h6>
                                            <p class="text-xs text-secondary mb-0 small opacity-7">Project:
                                                {{ $update->task?->project?->name ?? 'N/A' }}</p>
                                        </td>
                                        @unless (auth()->user()->hasRole('employee'))
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $update->employee?->name ?? 'Unknown' }}</p>
                                            </td>
                                        @endunless

                                        <td class="align-middle text-center text-sm" style="width: 15%">
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="badge badge-sm bg-gradient-info text-xxs mb-1">{{ number_format($update->hours_spent, 2) }} HRS</span>
                                                <p class="text-xxs text-secondary mb-0">{{ \Carbon\Carbon::parse($update->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($update->end_time)->format('h:i A') }}</p>
                                            </div>
                                        </td>
                                        <td style="width: 35%">
                                            <p class="text-xs font-weight-bold mb-1">
                                                {{ Str::limit($update->work_done, 150) }}</p>
                                        </td>
                                        <td class="align-middle text-end px-3">
                                            <div class="d-flex align-items-center justify-content-end gap-2">
                                                <a href="{{ route('daily-updates.show', $update->id) }}"
                                                    class="btn btn-link text-primary p-0 mb-0" data-bs-toggle="tooltip" title="View details">
                                                    <i class="fas fa-eye text-lg"></i>
                                                </a>
                                                @if (auth()->user()->hasAnyRole(['superadmin', 'admin']) || $update->employee_id === auth()->id())
                                                    <a href="{{ route('daily-updates.edit', $update->id) }}"
                                                        class="btn btn-link text-info p-0 mb-0" data-bs-toggle="tooltip" title="Edit log">
                                                        <i class="fas fa-edit text-lg"></i>
                                                    </a>
                                                    <form action="{{ route('daily-updates.destroy', $update->id) }}"
                                                        method="POST"
                                                        class="delete-form mb-0"
                                                        data-message="Are you sure you want to delete this daily work log? This action is permanent.">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-link text-danger mb-0 p-0 shadow-none border-0"
                                                            data-bs-toggle="tooltip" title="Delete log">
                                                            <i class="fas fa-trash text-lg"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-history text-secondary opacity-3 mb-3" style="font-size: 3rem;"></i>
                                                <h6 class="text-secondary opacity-7">No work updates found for the selected dates.</h6>
                                                <p class="text-xs text-secondary mb-0">Try adjusting your date range or search filters.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
