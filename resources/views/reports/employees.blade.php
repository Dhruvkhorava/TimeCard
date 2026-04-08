@extends('layouts.app')

@section('title', 'Employee Reports')

@section('content')
<div class="row" data-aos="fade-up">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Employee Work Reports</h6>
            </div>
                <!-- Filter Form Header Layout -->
                <div class="row mb-3 align-items-end p-3">
                    <div class="col-md-10">
                        <form method="GET" action="{{ route('reports.employees') }}" class="mb-0">
                            <div class="row align-items-end">
                                <div class="col-md-3">
                                    <label for="employee_id" class="form-label font-weight-bold text-xs text-uppercase">Employee</label>
                                    <select name="employee_id" id="employee_id" class="form-select form-select-sm">
                                        <option value="">All Employees</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                                {{ $emp->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="start_date" class="form-label font-weight-bold text-xs text-uppercase">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date" class="form-label font-weight-bold text-xs text-uppercase">End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn dark-blue btn-sm text-white mb-0">Filter</button>
                                    <button type="submit" formaction="{{ route('reports.employees.export') }}" class="btn bg-gradient-success btn-sm mb-0">
                                        <i class="fas fa-file-excel me-1"></i> Export Excel (CSV)
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2 text-end">
                        <div id="custom_search_container">
                            <!-- DataTable Search will be moved here via JS -->
                        </div>
                    </div>
                </div>

                <div class="table-responsive p-3">
                    <table class="table align-items-center mb-0" id="reportsTable" data-order-index="1" data-order-dir="desc">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Employee</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Project / Task</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Time / Hours</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Work Done</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($updates as $update)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $update->employee->name ?? 'N/A' }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ \Carbon\Carbon::parse($update->date)->format('M d, Y') }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <p class="text-xs font-weight-bold mb-0">{{ $update->task->project->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-secondary mb-0">{{ $update->task->name ?? 'N/A' }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="badge badge-sm bg-gradient-info mt-1">{{ $update->hours_spent ? $update->hours_spent . ' Hrs' : '-' }}</span>
                                    <span class="text-secondary text-xs font-weight-bold d-block">{{ $update->start_time ?? '-' }} to {{ $update->end_time ?? '-' }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold text-ellipsis max-width-200" 
                                          title="{{ $update->work_done }}">
                                        {{ $update->work_done ?? 'N/A' }}
                                    </span>
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
            // Check if the table exists and hasn't been initialized
            if ($('#reportsTable').length && !$.fn.DataTable.isDataTable('#reportsTable')) {
                const orderIndex = $('#reportsTable').data('order-index') || 0;
                const orderDir = $('#reportsTable').data('order-dir') || 'asc';
                
                initDataTable('#reportsTable', {
                    order: [[orderIndex, orderDir]],
                    // 't' is for the table, 'ip' for info and pagination at bottom
                    // We remove 'f' from here because we'll move it manually or it's not needed at the default top
                    dom: 'rt<"row mt-3"<"col-md-5"i><"col-md-7"p>>', 
                    initComplete: function() {
                        // Create the search input manually and link it to the table
                        const searchInput = $(`<input type="search" class="form-control form-control-sm ms-auto w-auto" placeholder="Search..." aria-controls="reportsTable">`);
                        $('#custom_search_container').append(searchInput);
                        
                        searchInput.on('keyup input', function() {
                            $('#reportsTable').DataTable().search(this.value).draw();
                        });
                    }
                });
            }
        });
    </script>
@endpush
@endsection

