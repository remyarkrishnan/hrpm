@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.admin')

@section('title', __('projects.reports.title', ['company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('projects.reports.page_title'))

@section('content')
<div class="page-header">
    <div>
        <p>{{ __('projects.reports.description') }}</p>
    </div>
</div>

<!-- Summary Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#4CAF50;">
            <i class="material-icons">people</i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalEmployees ?? 0 }}</h3>
            <p>{{ __('projects.reports.stats.total_employees') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#2196F3;">
            <i class="material-icons">timeline</i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalSteps ?? 0 }}</h3>
            <p>{{ __('projects.reports.stats.total_steps') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#F44336;">
            <i class="material-icons">assignment_late</i>
        </div>
        <div class="stat-info">
            <h3>{{ $delayedSubplans ?? 0 }}</h3>
            <p>{{ __('projects.reports.stats.delayed_subplans') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#FF9800;">
            <i class="material-icons">fact_check</i>
        </div>
        <div class="stat-info">
            <h3>{{ $pendingApprovals ?? 0 }}</h3>
            <p>{{ __('projects.reports.stats.pending_approvals') }}</p>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="shifts-table-card">
    <ul class="tab-menu">
        <li class="active" data-tab="allocations">{{ __('projects.reports.tabs.allocations') }}</li>
        <li data-tab="progress">{{ __('projects.reports.tabs.progress') }}</li>
        <li data-tab="approvals">{{ __('projects.reports.tabs.approvals') }}</li>
    </ul>

    <!-- Employee Allocation -->
    <div class="tab-content active" id="allocations">
        <div class="table-responsive">
            <table class="shifts-table">
                <thead>
                    <tr>
                        <th>{{ __('projects.reports.allocations.table.project') }}</th>
                        <th>{{ __('projects.reports.allocations.table.employee') }}</th>
                        <th>{{ __('projects.reports.allocations.table.role') }}</th>
                        <th>{{ __('projects.reports.allocations.table.allocation') }}</th>
                        <th>{{ __('projects.reports.allocations.table.remarks') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employeeAllocations as $alloc)
                        <tr>
                            <td>{{ $alloc->subplan->step->project->name ?? __('projects.reports.no_data') }}</td>
                            <td>{{ $alloc->employee->name }}</td>
                            <td>{{ $alloc->role ?? __('projects.reports.no_data') }}</td>
                            <td>{{ $alloc->allocation_percentage }}%</td>
                            <td>{{ $alloc->remarks ?? __('projects.reports.no_data') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">{{ __('projects.reports.allocations.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Step-wise Progress -->
    <div class="tab-content" id="progress">
        <div class="table-responsive">
            <table class="shifts-table">
                <thead>
                    <tr>
                        <th>{{ __('projects.reports.progress.table.project') }}</th>
                        <th>{{ __('projects.reports.progress.table.step') }}</th>
                        <th>{{ __('projects.reports.progress.table.subplan') }}</th>
                        <th>{{ __('projects.reports.progress.table.progress') }}</th>
                        <th>{{ __('projects.reports.progress.table.deadline') }}</th>
                        <th>{{ __('projects.reports.progress.table.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projectSteps as $step)
                        @foreach($step->subplans as $sub)
                            @php
                                $isDelayed = $sub->end_date && $sub->end_date < now() && ($sub->progress_percentage ?? 0) < 100;
                            @endphp
                            <tr>
                                <td>{{ $step->project->name ?? __('projects.reports.no_data') }}</td>
                                <td>{{ $step->step_name }}</td>
                                <td>{{ $sub->activity_name }}</td>
                                <td style="min-width:150px;">
                                    <div class="progress" style="height:8px;">
                                        <div class="progress-bar bg-success" style="width:{{ $sub->progress_percentage ?? 0 }}%;"></div>
                                    </div>
                                    <small>{{ $sub->progress_percentage ?? 0 }}%</small>
                                </td>
                                <td>{{ $sub->end_date ? \Carbon\Carbon::parse($sub->end_date)->format('d M Y') : __('projects.reports.no_deadline') }}</td>
                                <td>
                                    @if(($sub->progress_percentage ?? 0) >= 100)
                                        <span class="status-badge perf-excellent">{{ __('projects.reports.progress.status.completed') }}</span>
                                    @elseif($isDelayed)
                                        <span class="status-badge perf-poor">{{ __('projects.reports.progress.status.delayed') }}</span>
                                    @else
                                        <span class="status-badge perf-good">{{ __('projects.reports.progress.status.in_progress') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">{{ __('projects.reports.progress.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Approval Status -->
    <div class="tab-content" id="approvals">
        <div class="table-responsive">
            <table class="shifts-table">
                <thead>
                    <tr>
                        <th>{{ __('projects.reports.approvals.table.project') }}</th>
                        <th>{{ __('projects.reports.approvals.table.subplan') }}</th>
                        <th>{{ __('projects.reports.approvals.table.employee') }}</th>
                        <th>{{ __('projects.reports.approvals.table.allocation') }}</th>
                        <th>{{ __('projects.reports.approvals.table.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvalStatus as $status)
                        <tr>
                            <td>{{ $status->subplan->step->project->name ?? __('projects.reports.no_data') }}</td>
                            <td>{{ $status->subplan->activity_name ?? __('projects.reports.no_data') }}</td>
                            <td>{{ $status->employee->name }}</td>
                            <td>{{ $status->allocation_percentage }}%</td>
                            <td>
                                @if($status->approval_status == 'approved')
                                    <span class="status-badge perf-excellent">{{ __('projects.reports.approvals.status.approved') }}</span>
                                @elseif($status->approval_status == 'pending')
                                    <span class="status-badge perf-good">{{ __('projects.reports.approvals.status.pending') }}</span>
                                @else
                                    <span class="status-badge perf-poor">{{ __('projects.reports.approvals.status.rejected') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">{{ __('projects.reports.approvals.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.stat-card {
    display: flex;
    align-items: center;
    padding: 16px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 24px;
    margin-right: 16px;
}
.stat-info h3 {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
}
.stat-info p {
    margin: 4px 0 0;
    font-size: 14px;
    color: #555;
}

/* Tabs */
.tab-menu { 
    display:flex; 
    gap:12px; 
    margin-bottom:16px; 
    list-style:none; 
    padding:0; 
    border-bottom:2px solid #f0f0f0; 
    flex-wrap: wrap; 
}
.tab-menu li { 
    cursor:pointer; 
    padding:8px 20px; 
    border-radius:8px 8px 0 0; 
    background:#f5f5f5; 
    font-weight:500; 
}
.tab-menu li.active { 
    background:#6750A4; 
    color:white; 
}
.tab-content { display:none; }
.tab-content.active { display:block; }

/* Table */
.shifts-table { 
    width:100%; 
    border-collapse: collapse; 
    min-width: 600px; 
}
.shifts-table th, .shifts-table td { 
    padding: 12px 16px; 
    border-bottom: 1px solid #eee; 
    text-align: left; 
    vertical-align: middle; 
}
.shifts-table th { 
    background: #f9f9f9; 
    font-weight: 600; 
    color: #333; 
}
.table-responsive { 
    overflow-x: auto; 
}

/* Progress bar */
.progress { 
    background:#f0f0f0; 
    border-radius:6px; 
    height:10px; 
    width:100%; 
}
.progress-bar { 
    border-radius:6px; 
    height:100%; 
}

/* Status badges */
.status-badge { 
    padding:6px 14px; 
    border-radius:12px; 
    font-weight:600; 
    display:inline-block; 
    text-align:center; 
    min-width:80px; 
}
.perf-excellent { background:#E8F5E9; color:#2E7D32; }
.perf-good { background:#E3F2FD; color:#1565C0; }
.perf-poor { background:#FFEBEE; color:#C62828; }

/* Responsive */
@media (max-width: 768px) {
    .stats-grid { grid-template-columns: 1fr; }
    .tab-menu { flex-direction: column; gap: 8px; }
    .shifts-table th, .shifts-table td { padding: 8px 10px; font-size: 13px; }
    .progress { height:6px; }
}
</style>
@endpush

@push('scripts')
<script>
document.querySelectorAll('.tab-menu li').forEach(tab=>{
    tab.addEventListener('click',function(){
        document.querySelectorAll('.tab-menu li').forEach(t=>t.classList.remove('active'));
        this.classList.add('active');
        const tabId = this.dataset.tab;
        document.querySelectorAll('.tab-content').forEach(c=>c.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
    });
});
</script>
@endpush
