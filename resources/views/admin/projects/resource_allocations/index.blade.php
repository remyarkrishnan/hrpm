@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
    
    // compute performance buckets from allocations
    $counts = [
        'excellent' => 0,
        'good' => 0,
        'average' => 0,
        'poor' => 0,
    ];

    foreach($allocations as $a) {
        $p = (int) ($a->allocation_percentage ?? 0);
        if ($p >= 80) $counts['excellent']++;
        elseif ($p >= 50) $counts['good']++;
        elseif ($p >= 20) $counts['average']++;
        else $counts['poor']++;
    }

    $totalForChart = array_sum($counts) ?: 1; // avoid zero divide
@endphp

@extends('layouts.admin')

@section('title', __('projects.resource_allocations.title', ['activity' => $subplan->activity_name, 'company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('projects.resource_allocations.page_title', ['activity' => $subplan->activity_name]))

@section('content')
<div class="page-header">
    <div>
        <p>{{ __('projects.resource_allocations.description') }}</p>
    </div>
</div>

<!-- Summary Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#4CAF50;">
            <i class="material-icons">engineering</i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalEmployees }}</h3>
            <p>{{ __('projects.resource_allocations.stats.total_employees') }}</p>
        </div>
    </div>

    <div class="stat-card" style="display:none">
        <div class="stat-icon" style="background:#FF9800;">
            <i class="material-icons">build</i>
        </div>
        <div class="stat-info">
            <h3>{{ $activeProjects }}</h3>
            <p>{{ __('projects.resource_allocations.stats.active_projects') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#3F51B5;">
            <i class="material-icons">work_outline</i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalSubplans }}</h3>
            <p>{{ __('projects.resource_allocations.stats.total_subplans') }}</p>
        </div>
    </div>

    <!-- Chart Card -->
    <div class="stat-card" style="align-items: stretch;">
        <div style="flex: 1;">
            <h4 style="margin:0 0 8px 0; font-size:16px;">{{ __('projects.resource_allocations.performance_summary') }}</h4>
            anvas id="perfChart" width="200" height="140"></canvas>

            <div style="display:flex; gap:8px; margin-top:12px; flex-wrap:wrap;">
                <span style="display:inline-flex;align-items:center;gap:8px;">
                    <span style="width:12px;height:12px;background:#2E7D32;border-radius:3px;display:inline-block"></span>
                    <small>{{ __('projects.resource_allocations.performance.excellent', ['count' => $counts['excellent']]) }}</small>
                </span>
                <span style="display:inline-flex;align-items:center;gap:8px;">
                    <span style="width:12px;height:12px;background:#1565C0;border-radius:3px;display:inline-block"></span>
                    <small>{{ __('projects.resource_allocations.performance.good', ['count' => $counts['good']]) }}</small>
                </span>
                <span style="display:inline-flex;align-items:center;gap:8px;">
                    <span style="width:12px;height:12px;background:#F9A825;border-radius:3px;display:inline-block"></span>
                    <small>{{ __('projects.resource_allocations.performance.average', ['count' => $counts['average']]) }}</small>
                </span>
                <span style="display:inline-flex;align-items:center;gap:8px;">
                    <span style="width:12px;height:12px;background:#C62828;border-radius:3px;display:inline-block"></span>
                    <small>{{ __('projects.resource_allocations.performance.poor', ['count' => $counts['poor']]) }}</small>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Add assignment form -->
<div class="shifts-table-card mb-4">
    <h3>{{ __('projects.resource_allocations.assign_title') }}</h3>
    <form action="{{ route('admin.subplans.resources.store', $subplan->id) }}" method="POST" enctype="multipart/form-data" class="project-form">
        @csrf

        <!-- Basic Project Information -->
        <div class="form-section">
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">{{ __('projects.resource_allocations.form.employee_label') }} *</label>
                    <select name="employee_id" class="form-select" required>
                        <option value="">{{ __('projects.resource_allocations.form.select_employee') }}</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="project_code">{{ __('projects.resource_allocations.form.role_label') }}</label>
                    <input type="text" name="role" class="form-control" value="{{ old('role') }}">
                    @error('role')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="start_date">{{ __('projects.resource_allocations.form.allocation_label') }} *</label>
                    <input type="number" required name="allocation_percentage" class="form-control" min="0" max="100" value="{{ old('allocation_percentage') }}">
                    @error('allocation_percentage')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ __('projects.resource_allocations.form.remarks_label') }}</label>
                    <input type="text" name="remarks" class="form-control" value="{{ old('remarks') }}">
                    @error('remarks')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="form-actions full-width" style="display:flex; gap:12px; align-items:flex-end;">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="material-icons">save</i>
                        {{ __('projects.resource_allocations.form.create_button') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Allocation Table -->
<div class="shifts-table-card mb-4">
    <h3>{{ __('projects.resource_allocations.table_title', ['activity' => $subplan->activity_name]) }}</h3>
    <div class="table-responsive">
        <table class="shifts-table">
            <thead>
                <tr>
                    <th>{{ __('projects.resource_allocations.table.employee') }}</th>
                    <th>{{ __('projects.resource_allocations.table.allocation') }}</th>
                    <th>{{ __('projects.resource_allocations.table.performance') }}</th>
                    <th>{{ __('projects.resource_allocations.table.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allocations as $alloc)
                    @php
                        // performance badge derived from allocation_percentage
                        $p = (int) $alloc->allocation_percentage;
                        if ($p >= 80) $perfClass = 'perf-excellent';
                        elseif ($p >= 50) $perfClass = 'perf-good';
                        elseif ($p >= 20) $perfClass = 'perf-average';
                        else $perfClass = 'perf-poor';
                        
                        $perfLabel = match(true) {
                            $p >= 80 => __('projects.resource_allocations.performance_labels.excellent'),
                            $p >= 50 => __('projects.resource_allocations.performance_labels.good'),
                            $p >= 20 => __('projects.resource_allocations.performance_labels.average'),
                            default => __('projects.resource_allocations.performance_labels.poor')
                        };
                    @endphp
                    <tr>
                        <td>
                            <div class="shift-info">
                                <strong>{{ $alloc->employee->name }}</strong>
                                <small class="text-muted">{{ $alloc->employee->email }}</small>
                            </div>
                        </td>
                        <td>{{ $alloc->allocation_percentage }}%</td>
                        <td>
                            <span class="status-badge {{ $perfClass }}">
                                {{ $perfLabel }} ({{ $alloc->allocation_percentage }}%)
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.subplans.resources.destroy', [$subplan->id, $alloc->id]) }}" method="POST" onsubmit="return confirm('{{ __("projects.resource_allocations.delete_confirm") }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action delete" title="{{ __('projects.resource_allocations.delete_tooltip') }}">
                                    <i class="material-icons">delete</i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">{{ __('projects.resource_allocations.no_allocations') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* reuse your existing shift style look */
    .page-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:32px; }
    .btn-primary { display:inline-flex; align-items:center; gap:8px; background:#6750A4; color:white; padding:12px 20px; border-radius:8px; text-decoration:none; font-weight:500; transition:background .2s; }
    .btn-primary:hover { background:#5A4A94; }
    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:20px; margin-bottom:24px; }
    .stat-card { background:white; padding:20px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,.08); display:flex; gap:16px; align-items:center; }
    .stat-icon { width:56px; height:56px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:white; font-size:24px; }
    .shifts-table-card { background:white; padding:20px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,.06); margin-bottom:20px; }
    .shifts-table { width:100%; border-collapse:collapse; }
    .shifts-table th, .shifts-table td { padding:12px; border-bottom:1px solid #f0f0f0; text-align:left; vertical-align:middle; }
    .shift-info strong { display:block; }
    .status-badge { padding:6px 12px; border-radius:12px; font-weight:600; text-transform:none; display:inline-block; }
    .perf-excellent { background:#E8F5E9; color:#2E7D32; }
    .perf-good { background:#E3F2FD; color:#1565C0; }
    .perf-average { background:#FFF8E1; color:#F9A825; }
    .perf-poor { background:#FFEBEE; color:#C62828; }
    .btn-action { width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center; border-radius:8px; border:none; cursor:pointer; background:#f5f5f5; color:#666; }
    .btn-action.delete { background:#ffebee; color:#c62828; }
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
    .form-group { display: flex; flex-direction: column; }
    .form-group.full-width { grid-column: 1 / -1; }
    .form-group label { font-weight: 500; color: #333; margin-bottom: 8px; }
    .form-group input, .form-group select, .form-group textarea { padding: 12px 16px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; transition: border-color 0.2s; background: white; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #6750A4; }
    .form-group .error { color: #d32f2f; font-size: 12px; margin-top: 4px; }
    .form-help { color: #666; font-size: 12px; margin-top: 4px; }
    .form-actions { padding-top: 24px; }
    .btn-primary { display: flex; align-items: center; gap: 8px; background: #6750A4; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; text-decoration: none; transition: background 0.2s; }
    .btn-primary:hover { background: #5A4A94; }
    .btn-cancel { padding: 12px 24px; border: 2px solid #e0e0e0; border-radius: 8px; text-decoration: none; color: #666; font-weight: 500; transition: all 0.2s; }
    .btn-cancel:hover { border-color: #666; color: #333; }
    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-section { padding: 20px; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel { width: 100%; justify-content: center; }
        .workflow-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endpush

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('perfChart').getContext('2d');

    const data = {
        labels: [
            '{{ __("projects.resource_allocations.chart.excellent") }}',
            '{{ __("projects.resource_allocations.chart.good") }}',
            '{{ __("projects.resource_allocations.chart.average") }}',
            '{{ __("projects.resource_allocations.chart.poor") }}'
        ],
        datasets: [{
            data: [
                {{ $counts['excellent'] }},
                {{ $counts['good'] }},
                {{ $counts['average'] }},
                {{ $counts['poor'] }}
            ],
            backgroundColor: [
                '#2E7D32', // green
                '#1565C0', // blue
                '#F9A825', // yellow
                '#C62828'  // red
            ],
            hoverOffset: 6,
            borderWidth: 0
        }]
    };

    const config = {
        type: 'doughnut',
        data: data,
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 12
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const percent = (value / {{ $totalForChart }}) * 100;
                            return label + ': ' + value + ' (' + percent.toFixed(0) + '%)';
                        }
                    }
                }
            },
            cutout: '60%'
        }
    };

    // create canvas sizing
    const perfCanvas = document.getElementById('perfChart');
    perfCanvas.style.maxWidth = '220px';
    perfCanvas.style.maxHeight = '160px';

    new Chart(ctx, config);
});
</script>
@endpush
