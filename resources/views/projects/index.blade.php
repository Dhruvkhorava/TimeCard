@extends('layouts.app')

@section('title', 'Projects - Soft UI Dashboard')


@section('content')
    @php
        $totalProjects = $projects->count();
        $activeProjects = $projects->where('status', 'active')->count();
        $pendingProjects = $projects->where('status', 'pending')->count();
        $completedProjects = $projects->where('status', 'completed')->count();
    @endphp

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card bg-white shadow-blur">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Projects</p>
                                <h5 class="font-weight-bolder mb-0 text-primary">
                                    {{ $totalProjects }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-folder-17 text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card bg-white shadow-blur">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Active</p>
                                <h5 class="font-weight-bolder mb-0 text-success">
                                    {{ $activeProjects }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="ni ni-curved-next text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card bg-white shadow-blur">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Pending</p>
                                <h5 class="font-weight-bolder mb-0 text-warning">
                                    {{ $pendingProjects }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                <i class="ni ni-time-alarm text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card bg-white shadow-blur">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Completed</p>
                                <h5 class="font-weight-bolder mb-0 text-info">
                                    {{ $completedProjects }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4 border-0 shadow-lg">
                <div class="card-header pb-0 bg-transparent">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                            <h6 class="mb-0"><i class="ni ni-bullet-list-67 text-primary me-2"></i>Projects List</h6>
                            <p class="text-xs text-secondary mb-0">Manage and track your ongoing projects</p>
                        </div>
                        @hasanyrole('superadmin|admin|client')
                            <div class="col-lg-6 col-5 my-auto text-end">
                                <a href="{{ route('projects.create') }}"
                                    class="btn bg-gradient-primary btn-sm mb-0 px-4 border-radius-lg">
                                    <i class="fas fa-plus me-2"></i>New Project
                                </a>
                            </div>
                        @endhasanyrole
                    </div>
                </div>
                <div class="card-body px-0 pt-4 pb-2">
                    <div class="table-responsive p-0 px-4">
                        <table class="table align-items-center mb-0" id="projects-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Project
                                        Name</th>
                                    @unless (auth()->user()->hasRole('employee'))
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Client</th>
                                    @endunless
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Status</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Created At</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projects as $index => $project)
                                    <tr data-aos="fade-left" data-aos-delay="{{ 100 + ($index * 50) }}">
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    @php
                                                        $colors = ['bg-gradient-primary', 'bg-gradient-info', 'bg-gradient-success', 'bg-gradient-warning', 'bg-gradient-danger', 'bg-gradient-dark'];
                                                        $colorIndex = $project->id % count($colors);
                                                        $randomColor = $colors[$colorIndex];
                                                    @endphp
                                                    <div class="project-avatar {{ $randomColor }} me-3">
                                                        {{ strtoupper(substr($project->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm font-weight-bold text-dark">{{ $project->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                        {{ Str::limit($project->description, 40) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        @unless (auth()->user()->hasRole('employee'))
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-xs rounded-circle me-2 bg-light shadow-sm">
                                                        <i class="fas fa-user text-secondary" style="font-size: 0.6rem;"></i>
                                                    </div>
                                                    <p class="text-xs font-weight-bold mb-0 text-dark">
                                                        {{ $project->client->name ?? 'N/A' }}</p>
                                                </div>
                                            </td>
                                        @endunless
                                        <td class="align-middle text-center text-sm">
                                            @php
                                                $badgeClass = 'bg-gradient-secondary';
                                                if ($project->status == 'active') $badgeClass = 'bg-gradient-success';
                                                if ($project->status == 'completed') $badgeClass = 'bg-gradient-info';
                                                if ($project->status == 'pending') $badgeClass = 'bg-gradient-warning';
                                            @endphp
                                            <span class="badge badge-sm {{ $badgeClass }} border-radius-lg px-3">{{ ucfirst($project->status) }}</span>
                                        </td>
                                        <td class="align-middle text-center text-xs">
                                            <span class="text-secondary font-weight-bold">
                                                <i class="far fa-calendar-alt me-1 text-info"></i>
                                                {{ $project->created_at->format('d/M/Y') }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <a href="{{ route('projects.show', $project->id) }}"
                                                    class="btn btn-link text-primary px-3 mb-0" data-bs-toggle="tooltip" title="View details">
                                                    <i class="fas fa-eye text-primary"></i>
                                                    <span class="ms-1 d-none d-md-inline">View</span>
                                                </a>
                                                @if (auth()->user()->hasAnyRole(['superadmin', 'admin']))
                                                    <a href="{{ route('projects.edit', $project->id) }}"
                                                        class="btn btn-link text-info px-3 mb-0" data-bs-toggle="tooltip" title="Edit project">
                                                        <i class="fas fa-edit text-info"></i>
                                                        <span class="ms-1 d-none d-md-inline">Edit</span>
                                                    </a>
                                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="delete-form d-inline-block" data-message="Deleting a project will remove all associated tasks and logs. This cannot be undone.">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger px-3 mb-0" data-bs-toggle="tooltip" title="Delete Project">
                                                            <i class="far fa-trash-alt text-danger"></i>
                                                            <span class="ms-1 d-none d-md-inline">Delete</span>
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
                initDataTable('#projects-table', {
                    "pageLength": 10,
                    "language": {
                        "searchPlaceholder": "Search projects..."
                    }
                });
            });
        </script>
    @endpush
@endsection
