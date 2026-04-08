@extends('layouts.app')

@section('title', 'Tasks - Soft UI Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm border-0" data-aos="fade-up">
                <div class="card-header pb-0 bg-white border-bottom">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                            <h6 class="font-weight-bolder text-color-blue mb-1">Tasks Management</h6>
                            <p class="text-sm mb-0">
                                <i class="fa fa-tasks text-info" aria-hidden="true"></i>
                                <span class="font-weight-bold ms-1">Detailed list</span> of all assigned tasks
                            </p>
                        </div>
                        @if (auth()->user()->hasAnyRole(['superadmin', 'admin']))
                            <div class="col-lg-6 col-5 my-auto text-end">
                                <a href="{{ route('tasks.create') }}" class="btn bg-gradient-dark btn-sm mb-0">
                                    <i class="fas fa-plus me-2"></i>Assign Task
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-3">
                        <table class="table align-items-center mb-0" id="tasks-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Task Details</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Project</th>
                                    @unless (auth()->user()->hasRole('employee'))
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Assigned To</th>
                                    @endunless
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $index => $task)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm font-weight-bold text-dark">{{ $task->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                        {{ Str::limit($task->description, 60) }}
                                                        @if($task->phase)
                                                            <span class="badge badge-sm bg-light text-dark ms-2" style="font-size: 0.65rem;">
                                                                <i class="fas fa-layer-group me-1 opacity-5"></i>{{ $task->phase->name }}
                                                            </span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm border border-primary text-primary bg-transparent font-weight-bold">
                                                {{ $task->project?->name ?? 'No Project' }}
                                            </span>
                                        </td>
                                        @unless (auth()->user()->hasRole('employee'))
                                            <td>
                                                <div class="avatar-group mt-2">
                                                    @forelse ($task->employees as $employee)
                                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $employee->name }}">
                                                          <img src="{{ $employee->profile_image_url ?? asset('img/default-avatar.png') }}" alt="{{ $employee->name }}">
                                                        </a>
                                                    @empty
                                                        <span class="text-xs text-secondary">Unassigned</span>
                                                    @endforelse
                                                </div>
                                            </td>
                                        @endunless

                                        <td class="align-middle text-center text-sm">
                                            @php
                                                $badgeClass = 'bg-gradient-secondary';
                                                $icon = 'fa-clock';
                                                if ($task->status == 'in_progress') {
                                                    $badgeClass = 'bg-gradient-info';
                                                    $icon = 'fa-spinner fa-spin';
                                                } elseif ($task->status == 'completed') {
                                                    $badgeClass = 'bg-gradient-success';
                                                    $icon = 'fa-check-circle';
                                                } elseif ($task->status == 'not_started') {
                                                    $badgeClass = 'bg-gradient-warning';
                                                    $icon = 'fa-pause-circle';
                                                }
                                            @endphp

                                            @if (auth()->user()->hasRole('employee') && $task->status != 'completed')
                                                <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="mt-2 status-form d-inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="input-group input-group-sm mb-0">
                                                        <select name="status" class="form-select form-select-sm border-light text-xs font-weight-bold" onchange="this.form.submit()" style="min-width: 120px;">
                                                            <option value="not_started" @selected($task->status == 'not_started')>Not Started</option>
                                                            <option value="in_progress" @selected($task->status == 'in_progress')>In Progress</option>
                                                            <option value="completed" @selected($task->status == 'completed')>Completed</option>
                                                        </select>
                                                    </div>
                                                </form>
                                            @else
                                                <span class="badge badge-sm {{ $badgeClass }}">
                                                    <i class="fas {{ $icon }} me-1"></i>
                                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                @if (auth()->user()->hasRole('employee') && $task->status != 'completed')
                                                    <a href="{{ route('daily-updates.index', ['task_id' => $task->id]) }}"
                                                        class="btn btn-link text-info px-3 mb-0" data-bs-toggle="tooltip" title="Update Daily Progress">
                                                        <i class="fas fa-edit text-info" aria-hidden="true"></i>
                                                        <span class="ms-1 font-weight-bold">Update</span>
                                                    </a>
                                                @endif
                                                @if (auth()->user()->hasAnyRole(['superadmin', 'admin']))
                                                    <a href="{{ route('tasks.edit', $task->id) }}"
                                                        class="btn btn-link text-dark px-3 mb-0" data-bs-toggle="tooltip" title="Modify Task">
                                                        <i class="fas fa-pencil-alt text-dark" aria-hidden="true"></i>
                                                        <span class="ms-1 font-weight-bold text-xs uppercase">Edit</span>
                                                    </a>
                                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="delete-form d-inline-block" data-message="This will permanently delete the task assignment.">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger px-3 mb-0" data-bs-toggle="tooltip" title="Delete Task">
                                                            <i class="far fa-trash-alt text-danger" aria-hidden="true"></i>
                                                            <span class="ms-1 font-weight-bold text-xs uppercase">Delete</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            $(document).ready(function() {
                initDataTable('#tasks-table', {
                    "language": {
                        "searchPlaceholder": "Search tasks..."
                    }
                });
                
                // Initialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                  return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            });
        </script>
    @endpush
@endsection

