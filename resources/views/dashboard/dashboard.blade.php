@extends('layouts.app')

@section('title', 'Dashboard - Timecard Management')

@section('content')
<div class="row">
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4" data-aos="fade-up">
    <div class="card card-hover-glow">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Projects</p>
              <h5 class="font-weight-bolder mb-0">
                <span class="count-up" data-value="{{ $stats['total_projects'] }}">0</span>
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
              <i class="fas fa-folder text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4" data-aos="fade-up" data-aos-delay="100">
    <div class="card card-hover-glow">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Tasks</p>
              <h5 class="font-weight-bolder mb-0">
                <span class="count-up" data-value="{{ $stats['total_tasks'] }}">0</span>
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
              <i class="fas fa-tasks text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4" data-aos="fade-up" data-aos-delay="200">
    <div class="card card-hover-glow">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Employees</p>
              <h5 class="font-weight-bolder mb-0">
                <span class="count-up" data-value="{{ $stats['total_users'] }}">0</span>
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
              <i class="fas fa-users text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6" data-aos="fade-up" data-aos-delay="300">
    <div class="card card-hover-glow">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">@if($role === 'admin') Total Clients @else Logged This Month @endif</p>
              <h5 class="font-weight-bolder mb-0">
                <span class="count-up" data-value="{{ $role === 'admin' ? $stats['total_clients'] : ($stats['hours_this_month'] ?? 0) }}">0</span> @if($role !== 'admin') HRS @endif
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
              <i class="fas fa-{{ $role === 'admin' ? 'user-tie' : 'clock' }} text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-lg-7 mb-lg-0 mb-4">
    <div class="card z-index-2">
      <div class="card-header pb-0">
        <h6>Hours Logged (Last 7 Days)</h6>
        <p class="text-sm">
          <i class="fa fa-arrow-up text-success"></i>
          <span class="font-weight-bold">Recent</span> activity breakdown
        </p>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-5 col-md-12">
    <div class="card h-100">
      <div class="card-header pb-0">
        <h6>Recent Work Updates</h6>
        <p class="text-sm">
          <i class="fa fa-history text-info" aria-hidden="true"></i>
          <span class="font-weight-bold">Latest logs</span>
        </p>
      </div>
      <div class="card-body p-3">
        <div class="timeline timeline-one-side">
          @forelse($timeline as $update)
          <div class="timeline-block mb-3">
            <span class="timeline-step">
              <i class="fas fa-check-circle text-success text-gradient"></i>
            </span>
            <div class="timeline-content">
              <h6 class="text-dark text-sm font-weight-bold mb-0">
                @if($update->employee)
                  {{ $update->employee->name }}: 
                @endif
                {{ $update->hours_spent }} HRS
              </h6>
              <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ Str::limit($update->work_done, 60) }}</p>
              <p class="text-secondary text-xxs mt-1 mb-0">{{ \Carbon\Carbon::parse($update->date)->format('d M Y') }}</p>
            </div>
          </div>
          @empty
          <div class="text-center py-5">
              <p class="text-xs text-secondary mb-0">No recent updates.</p>
          </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row my-4">
  <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        <div class="row">
          <div class="col-lg-6 col-7">
            <h6>Recent Projects</h6>
            <p class="text-sm mb-0">
              <i class="fa fa-folder-open text-info" aria-hidden="true"></i>
              <span class="font-weight-bold ms-1">Latest active</span> projects
            </p>
          </div>
        </div>
      </div>
      <div class="card-body px-0 pb-2">
        <div class="table-responsive">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Project Name</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Client / Owner</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tasks</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Completion</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentProjects as $project)
              <tr>
                <td>
                  <div class="d-flex px-2 py-1">
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="mb-0 text-sm ps-3">{{ $project->name }}</h6>
                    </div>
                  </div>
                </td>
                <td>
                  <p class="text-xs font-weight-bold mb-0">{{ $project->client->name ?? 'Internal' }}</p>
                </td>
                <td class="align-middle text-center text-sm">
                  <span class="text-xs font-weight-bold"> {{ $project->tasks->count() }} </span>
                </td>
                <td class="align-middle">
                  <div class="progress-wrapper w-75 mx-auto">
                    <div class="progress-info">
                      <div class="progress-percentage">
                        <span class="text-xs font-weight-bold">{{ $project->progress ?? 0 }}%</span>
                      </div>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bg-gradient-info" style="width: {{ min(($project->progress ?? 0), 100) }}%" role="progressbar" aria-valuenow="{{ $project->progress ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center py-4 text-xs text-secondary">No records found.</td>
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

@push('js')
<script src="{{ asset('js/plugins/chartjs.min.js') }}"></script>
<script>
  var ctx2 = document.getElementById("chart-line").getContext("2d");

  var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

  gradientStroke1.addColorStop(1, 'rgba(50,149,253,0.2)');
  gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
  gradientStroke1.addColorStop(0, 'rgba(50,149,253,0)');

  var chartLabels = @json($chartData['labels']);
  var chartData = @json($chartData['hours']);

  new Chart(ctx2, {
    type: "line",
    data: {
      labels: chartLabels,
      datasets: [{
          label: "Hours Logged",
          tension: 0.4,
          borderWidth: 0,
          pointRadius: 0,
          borderColor: "#3295FD",
          borderWidth: 3,
          backgroundColor: gradientStroke1,
          fill: true,
          data: chartData,
          maxBarThickness: 6
        }
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        }
      },
      interaction: {
        intersect: false,
        mode: 'index',
      },
      scales: {
        y: {
          grid: {
            drawBorder: false,
            display: true,
            drawOnChartArea: true,
            drawTicks: false,
            borderDash: [5, 5]
          },
          ticks: {
            display: true,
            padding: 10,
            color: '#b2b9bf',
            font: {
              size: 11,
              family: "Open Sans",
              style: 'normal',
              lineHeight: 2
            },
          }
        },
        x: {
          grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false,
            borderDash: [5, 5]
          },
          ticks: {
            display: true,
            color: '#b2b9bf',
            padding: 20,
            font: {
              size: 11,
              family: "Open Sans",
              style: 'normal',
              lineHeight: 2
            },
          }
        },
      },
    },
  });
</script>
<script>
  $(document).ready(function() {
    // CountUp for stats
    $('.count-up').each(function() {
      var $this = $(this);
      var value = parseFloat($this.data('value')) || 0;
      var numAnim = new CountUp(this, 0, value, 0, 2.5, {
        useEasing: true,
        useGrouping: true,
        separator: ',',
        decimal: '.'
      });
      if (!numAnim.error) {
        numAnim.start();
      } else {
        console.error(numAnim.error);
        $this.text(value);
      }
    });
  });
</script>
@endpush
