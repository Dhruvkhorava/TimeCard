@extends('layouts.app')

@section('title', 'Admins - Soft UI Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm border-0" data-aos="fade-up">
                <div class="card-header pb-0 bg-white border-bottom">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                            <h6 class="font-weight-bolder text-color-blue mb-1">Admins Management</h6>
                            <p class="text-sm mb-0">
                                <i class="fa fa-user-shield text-info" aria-hidden="true"></i>
                                <span class="font-weight-bold ms-1">System Administrators</span> with full access
                            </p>
                        </div>
                        <div class="col-lg-6 col-5 my-auto text-end">
                            <a href="{{ route('admin.create') }}" class="btn bg-gradient-dark btn-sm mb-0">
                                <i class="fas fa-plus me-2"></i>New Admin
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-3">
                        <table class="table align-items-center mb-0" id="admins-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created At</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admins as $index => $admin)
                                    <tr data-aos="fade-left" data-aos-delay="{{ 100 + ($index * 50) }}">
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <div>
                                                    <img src="{{ $admin->profile_image_url }}" class="avatar avatar-sm me-3 border-radius-lg shadow-sm" alt="user1">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm font-weight-bold text-dark">{{ $admin->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">Administrator</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0 text-color-lightblue">{{ $admin->email }}</p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                <i class="far fa-calendar-alt me-1"></i>{{ $admin->created_at->format('d M, Y') }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a href="{{ route('admin.edit', $admin->id) }}"
                                                    class="btn btn-link text-info px-3 mb-0" data-bs-toggle="tooltip" title="Edit Admin">
                                                    <i class="fas fa-pencil-alt text-info" aria-hidden="true"></i>
                                                    <span class="ms-1">Edit</span>
                                                </a>
                                                <form action="{{ route('admin.destroy', $admin->id) }}" method="POST" class="delete-form d-inline-block" data-message="All records for this administrator will be permanently removed.">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger px-3 mb-0" data-bs-toggle="tooltip" title="Delete Admin">
                                                        <i class="far fa-trash-alt text-danger" aria-hidden="true"></i>
                                                        <span class="ms-1">Delete</span>
                                                    </button>
                                                </form>

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
                initDataTable('#admins-table', {
                    "language": {
                        "searchPlaceholder": "Search admins..."
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
