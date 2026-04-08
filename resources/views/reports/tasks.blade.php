@extends('layouts.app')

@section('title', 'Employee Tasks')

@section('content')
<div class="row" data-aos="fade-up">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 border-bottom-0">
                <h6>Task Assignments Overview</h6>
                <p class="text-sm mb-0">View all assigned tasks grouped by employee.</p>
            </div>
            <div class="card-body px-4 pt-4 pb-2">
                <div class="table-responsive p-3">
                    <table class="table align-items-center mb-0 datatable-init" id="employeeTasksTable">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Employee</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Project</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Task Assigned</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                                @if($employee->tasks->count() > 0)
                                    @foreach($employee->tasks as $task)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $employee->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $employee->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $task->project->name ?? 'N/A' }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $task->name }}</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            @if($task->status == 'completed')
                                                <span class="badge badge-sm bg-gradient-success">Completed</span>
                                            @elseif($task->status == 'in_progress')
                                                <span class="badge badge-sm bg-gradient-info">In Progress</span>
                                            @else
                                                <span class="badge badge-sm bg-gradient-secondary">Not Started</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $employee->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $employee->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="3" class="text-center">
                                            <span class="text-xs text-secondary">No tasks assigned to this employee.</span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

