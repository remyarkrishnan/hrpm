@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.admin')

@section('title', __('projects.progress.title', ['project' => $project->name]))
@section('page-title', __('projects.progress.page_title', ['project' => $project->name]))

@section('content')
<div class="page-header">
    <div>
        <p>{{ __('projects.progress.description') }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.projects.index') }}" class="btn-primary" style="margin: 10px 0">
            <i class="material-icons">arrow_back</i>
            {{ __('projects.progress.back_button') }}
        </a>
    </div>
</div>

<!-- Summary Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#4CAF50;">
            <i class="material-icons">check_circle</i>
        </div>
        <div class="stat-info">
            <h3>{{ $completion ?? 0 }}%</h3>
            <p>{{ __('projects.progress.overall_completion') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#F44336;">
            <i class="material-icons">warning</i>
        </div>
        <div class="stat-info">
            <h3>{{ $delayed ?? 0 }}</h3>
            <p>{{ __('projects.progress.delayed_subplans') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#2196F3;">
            <i class="material-icons">timeline</i>
        </div>
        <div class="stat-info">
            <h3>{{ $project->steps->count() ?? 0 }}</h3>
            <p>{{ __('projects.progress.steps_count') }}</p>
        </div>
    </div>
</div>

<!-- Project Progress Table -->
<div class="shifts-table-card">
    <h3>{{ __('projects.progress.overview_title') }}</h3>
    <div class="table-responsive">
        <table class="shifts-table">
            <thead>
                <tr>
                    <th>{{ __('projects.progress.table.step') }}</th>
                    <th>{{ __('projects.progress.table.subplan') }}</th>
                    <th>{{ __('projects.progress.table.progress') }}</th>
                    <th>{{ __('projects.progress.table.deadline') }}</th>
                    <th>{{ __('projects.progress.table.status') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($project->steps as $step)
                    @foreach($step->subplans as $sub)
                        @php
                            $isDelayed = isset($sub->end_date) && $sub->end_date < now() && ($sub->progress_percentage ?? 0) < 100;
                        @endphp
                        <tr>
                            <td>{{ $step->step_name }}</td>
                            <td>{{ $sub->activity_name }}</td>
                            <td style="min-width:200px;">
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: {{ $sub->progress_percentage ?? 0 }}%;"></div>
                                </div>
                                <small>{{ $sub->progress_percentage ?? 0 }}%</small>
                            </td>
                            <td>{{ $sub->end_date ? \Carbon\Carbon::parse($sub->end_date)->format('d M Y') : __('projects.progress.no_deadline') }}</td>
                            <td>
                                @if(($sub->progress_percentage ?? 0) >= 100)
                                    <span class="status-badge perf-excellent">{{ __('projects.progress.status.completed') }}</span>
                                @elseif($isDelayed)
                                    <span class="status-badge perf-poor">{{ __('projects.progress.status.delayed') }}</span>
                                @else
                                    <span class="status-badge perf-good">{{ __('projects.progress.status.in_progress') }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">{{ __('projects.progress.no_data') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Gantt Chart -->
<div class="shifts-table-card">
    <h3>{{ __('projects.progress.gantt_title') }}</h3>
    anvas id="ganttChart" height="150"></canvas>
</div>
@endsection

@push('styles')
<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}
.stat-card {
    display:flex;
    align-items:center;
    gap:12px;
    padding:20px;
    background:white;
    border-radius:12px;
    box-shadow:0 2px 8px rgba(0,0,0,.08);
}
.stat-icon {
    width:50px; height:50px; border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    font-size:24px; color:white;
}
.stat-info h3 { margin:0; font-size:20px; font-weight:600; }
.stat-info p { margin:4px 0 0; color:#333; font-size:14px; }
.shifts-table-card { 
    background:white; 
    padding:20px; 
    border-radius:12px; 
    box-shadow:0 2px 8px rgba(0,0,0,.06); 
    margin-bottom:20px; 
}
.shifts-table { 
    width:100%; 
    border-collapse:collapse; 
}
.shifts-table th, .shifts-table td { 
    padding:12px; 
    border-bottom:1px solid #f0f0f0; 
    vertical-align:middle; 
    text-align:left; 
}
.shifts-table th { 
    background:#f5f5f5; 
    font-weight:600; 
}
.progress { 
    background:#f0f0f0; 
    border-radius:6px; 
    height:8px; 
    margin-bottom:4px; 
}
.progress-bar { 
    border-radius:6px; 
    background:#4CAF50; 
    height:100%; 
}
.status-badge { 
    padding:4px 10px; 
    border-radius:12px; 
    font-weight:600; 
    display:inline-block; 
    font-size:12px; 
    text-transform:none; 
}
.perf-excellent { background:#E8F5E9; color:#2E7D32; }
.perf-good { background:#E3F2FD; color:#1565C0; }
.perf-poor { background:#FFEBEE; color:#C62828; }
.header-actions .btn-primary { 
    display:inline-flex; 
    align-items:center; 
    gap:8px; 
    background:#6750A4; 
    color:white; 
    padding:10px 16px; 
    border-radius:8px; 
    text-decoration:none; 
    font-weight:500; 
}
.header-actions .btn-primary:hover { background:#5A4A94; }
.table-responsive { overflow-x:auto; }
@media(max-width:768px){
    .stats-grid{grid-template-columns:1fr;}
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('ganttChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($project->steps->flatMap->subplans->pluck('activity_name')),
        datasets: [{
            label: '{{ __("projects.progress.chart.progress_label") }}',
            data: @json($project->steps->flatMap->subplans->pluck('progress_percentage')),
            backgroundColor: '#6750A4'
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        scales: { 
            x: { 
                max: 100, 
                title:{
                    display:true, 
                    text:'{{ __("projects.progress.chart.complete_percent") }}'
                } 
            } 
        },
        plugins: { 
            legend: { display: false } 
        }
    }
});
</script>
@endpush
