@extends('layouts.app')

@section('title', 'Clients - Soft UI Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm border-0" data-aos="fade-up">
                <div class="card-header pb-0 bg-white border-bottom">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                            <h6 class="font-weight-bolder text-color-blue mb-1">Clients Management</h6>
                            <p class="text-sm mb-0">
                                <i class="fa fa-handshake text-info" aria-hidden="true"></i>
                                <span class="font-weight-bold ms-1">Business Partners</span> and client accounts
                            </p>
                        </div>
                        <div class="col-lg-6 col-5 my-auto text-end">
                            <a href="{{ route('client.create') }}" class="btn bg-gradient-dark btn-sm mb-0">
                                <i class="fas fa-plus me-2"></i>New Client
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-3">
                        <table class="table align-items-center mb-0" id="clients-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Partner</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Established</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $index => $client)
                                    <tr data-aos="fade-left" data-aos-delay="{{ 100 + ($index * 50) }}">
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <div>
                                                    <img src="{{ $client->profile_image_url }}" class="avatar avatar-sm me-3 border-radius-lg shadow-sm" alt="client">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm font-weight-bold text-dark">{{ $client->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $client->department ?? 'Corporate' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0 text-color-lightblue">{{ $client->email }}</p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                <i class="far fa-calendar-check me-1"></i>{{ $client->created_at->format('d M, Y') }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a href="{{ route('client.edit', $client->id) }}"
                                                    class="btn btn-link text-info px-3 mb-0" data-bs-toggle="tooltip" title="Edit Client">
                                                    <i class="fas fa-pencil-alt text-info" aria-hidden="true"></i>
                                                    <span class="ms-1">Edit</span>
                                                </a>
                                                <form action="{{ route('client.destroy', $client->id) }}" method="POST" class="delete-form d-inline-block" data-message="Deleting this client will also affect their projects and tasks. Proceed with caution.">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger px-3 mb-0" data-bs-toggle="tooltip" title="Delete Client">
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
                initDataTable('#clients-table', {
                    "language": {
                        "searchPlaceholder": "Search clients..."
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
