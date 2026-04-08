@extends('layouts.app')

@section('title', 'Project Dashboard - ' . $project->name)

@section('content')
<div class="container-fluid py-4">
    <!-- Header Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-body blur shadow-blur overflow-hidden border-0">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative bg-gradient-primary border-radius-lg">
                            <i class="fas fa-rocket text-white"></i>
                        </div>
                    </div>
                    <div class="col my-auto">
                        <div class="h-100">
                            <h5 class="mb-1 font-weight-bolder">
                                {{ $project->name }}
                            </h1>
                            <p class="mb-0 font-weight-bold text-sm">
                                Project Overview & Milestones
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                        <div class="nav-wrapper position-relative end-0">
                            <div class="d-flex justify-content-end align-items-center">
                                <a href="{{ route('projects.index') }}" class="btn btn-outline-primary btn-sm mb-0 me-2">Back to List</a>
                                @if (auth()->user()->hasAnyRole(['superadmin', 'admin']))
                                    <a href="{{ route('projects.edit', $project->id) }}" class="btn bg-gradient-dark btn-sm mb-0">Edit Project</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Project Info -->
        <div class="col-lg-8">
            <!-- Progress Summary -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Overall Progress</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $project->progress }}%
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-chart-bar-32 text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="progress-wrapper mt-3">
                        <div class="progress-info">
                            <div class="progress-percentage">
                                <span class="text-xs font-weight-bold">{{ $project->progress }}% completed</span>
                            </div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-primary" role="progressbar" aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $project->progress }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header pb-0 bg-transparent">
                    <h6 class="text-uppercase text-muted text-xs font-weight-bolder">Project Description</h6>
                </div>
                <div class="card-body pt-0">
                    <p class="text-dark opacity-8">
                        {{ $project->description ?: 'No detailed description provided for this project.' }}
                    </p>
                </div>
            </div>

            <!-- Phases & Tasks Dashboard -->
            <h6 class="mb-3 ps-2 font-weight-bolder text-uppercase text-xs text-muted">Project Milestones & Deliverables</h6>
            @forelse($project->phases as $phase)
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <span class="badge badge-dot me-2">
                                <i class="bg-primary"></i>
                            </span>
                            {{ $phase->name }}
                        </h6>
                        <span class="badge bg-light text-dark text-xxs px-2 py-1">
                            {{ $phase->tasks->count() }} Tasks
                        </span>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Task</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Assignee</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($phase->tasks as $task)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm font-weight-bold">{{ $task->name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-xs me-2 border-radius-sm bg-light">
                                                        <span class="text-xxs text-dark">{{ substr($task->employee->name ?? '?', 0, 1) }}</span>
                                                    </div>
                                                    <span class="text-xs font-weight-bold">{{ $task->employee->name ?? 'Unassigned' }}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                @php
                                                    $statusColor = 'warning';
                                                    if($task->status == 'completed') $statusColor = 'success';
                                                    if($task->status == 'in_progress') $statusColor = 'info';
                                                @endphp
                                                <span class="badge badge-sm bg-gradient-{{ $statusColor }}">
                                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-end px-4">
                                                <a href="{{ route('tasks.show', $task->id) }}" class="text-primary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="View details">
                                                    Details
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 bg-light-soft border-radius-lg">
                                                <p class="text-xs text-muted mb-0">No tasks assigned to this phase yet.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card card-body border-0 shadow-sm text-center py-5">
                    <i class="fas fa-layer-group text-muted mb-3 fa-2x"></i>
                    <p class="text-sm text-muted">No phases defined for this project.</p>
                </div>
            @endforelse
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header pb-0">
                    <h6>Project Metadata</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                    <i class="ni ni-badge text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">Status</h6>
                                    <span class="text-xs">Current Project Phase</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center text-sm font-weight-bold">
                                @php
                                    $pClass = 'warning';
                                    if ($project->status == 'active') $pClass = 'success';
                                    if ($project->status == 'completed') $pClass = 'info';
                                @endphp
                                <span class="badge bg-gradient-{{ $pClass }}">{{ ucfirst($project->status) }}</span>
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                    <i class="ni ni-single-02 text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">Client</h6>
                                    <span class="text-xs">Project Owner</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center text-sm font-weight-bold text-dark">
                                {{ $project->client->name ?? 'Internal Project' }}
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                    <i class="ni ni-calendar-grid-58 text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">Created At</h6>
                                    <span class="text-xs">Date of Initiation</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center text-sm font-weight-bold text-dark">
                                {{ $project->created_at->format('M d, Y') }}
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Total Stats -->
            <div class="row">
                <div class="col-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 text-center">
                            <h6>{{ $project->phases->count() }}</h6>
                            <p class="text-xs text-muted mb-0">Total Phases</p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 text-center">
                            <h6>{{ $project->tasks->count() }}</h6>
                            <p class="text-xs text-muted mb-0">Total Tasks</p>
                        </div>
                    </div>
                </div>
            </div>

            @if (auth()->user()->hasAnyRole(['superadmin', 'admin']))
                <div class="mt-4">
                    <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn btn-primary w-100 mb-0">
                        <i class="fas fa-plus me-2"></i> Quick Add Task
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
