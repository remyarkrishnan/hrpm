@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.admin')

@section('title', __('overtime.index.title', ['company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('overtime.index.page_title'))

@section('content')
<div class="page-header">
    <div>
        <p>{{ __('overtime.index.description') }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.overtime.create') }}" class="btn-primary">
            <i class="material-icons">add</i>
            {{ __('overtime.index.request_button') }}
        </a>
    </div>
</div>

<!-- Overtime Stats -->
<div class="stats-grid">
    <div class="stat-card pending">
        <div class="stat-icon">
            <i class="material-icons">pending_actions</i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats->pending ?? 0 }}</h3>
            <p>{{ __('overtime.index.stats.pending') }}</p>
        </div>
    </div>

    <div class="stat-card approved">
        <div class="stat-icon">
            <i class="material-icons">check_circle</i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats->approved ?? 0 }}</h3>
            <p>{{ __('overtime.index.stats.approved') }}</p>
        </div>
    </div>

    <div class="stat-card hours">
        <div class="stat-icon">
            <i class="material-icons">schedule</i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats->hours ?? 0 }}</h3>
            <p>{{ __('overtime.index.stats.hours') }}</p>
        </div>
    </div>

    <div class="stat-card cost">
        <div class="stat-icon">
            <i class="material-icons">attach_money</i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats->cost ?? '₹0.00' }}</h3>
            <p>{{ __('overtime.index.stats.cost') }}</p>
        </div>
    </div>
</div>

<!-- Overtime Requests Table -->
<div class="overtime-table-card">
    <h3>{{ __('overtime.index.list_title') }}</h3>
    <div class="table-responsive">
        <table class="overtime-table">
            <thead>
                <tr>
                    <th>{{ __('overtime.index.table.employee') }}</th>
                    <th>{{ __('overtime.index.table.project') }}</th>
                    <th>{{ __('overtime.index.table.date') }}</th>
                    <th>{{ __('overtime.index.table.hours') }}</th>
                    <th>{{ __('overtime.index.table.reason') }}</th>
                    <th>{{ __('overtime.index.table.status') }}</th>
                    <th>{{ __('overtime.index.table.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($overtimes as $overtime)
                <tr>
                    <td>
                        <div class="employee-info">
                            <div class="employee-avatar">{{ strtoupper(substr($overtime->employee_name, 0, 1)) }}</div>
                            <div>
                                <strong>{{ $overtime->employee_name }}</strong>
                                <small>{{ $overtime->employee_code }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $overtime->project ?? '-' }}</td>
                    <td>{{ $overtime->date }}</td>
                    <td><span class="overtime-hours">{{ $overtime->hours }} {{ __('overtime.show.hour_short') }}</span></td>
                    <td>{{ Str::limit($overtime->reason, 30) }}</td>
                    <td>
                        <span class="status-badge status-{{ $overtime->status }}">
                            {{ ucfirst($overtime->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.overtime.show', $overtime->id) }}" class="btn-action">
                                <i class="material-icons">visibility</i>
                            </a>
                            @if($overtime->status === 'pending')
                                <button class="btn-action btn-approve" onclick="approveOvertime({{ $overtime->id }})">
                                    <i class="material-icons">check</i>
                                </button>
                                <button class="btn-action btn-reject" onclick="rejectOvertime({{ $overtime->id }})">
                                    <i class="material-icons">close</i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">{{ __('overtime.index.no_requests') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 32px;
    }

    .page-header h2 {
        margin: 0 0 8px 0;
        font-size: 24px;
        font-weight: 500;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #6750A4;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.2s;
    }

    .btn-primary:hover { background: #5A4A94; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    .stat-card.pending .stat-icon { background: #FF9800; }
    .stat-card.approved .stat-icon { background: #4CAF50; }
    .stat-card.hours .stat-icon { background: #2196F3; }
    .stat-card.cost .stat-icon { background: #9C27B0; }

    .stat-info h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .stat-info p {
        margin: 4px 0 0 0;
        color: #666;
        font-size: 14px;
    }

    .overtime-table-card {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .overtime-table-card h3 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .overtime-table {
        width: 100%;
        border-collapse: collapse;
    }

    .overtime-table th {
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #f0f0f0;
        font-weight: 600;
        color: #333;
    }

    .overtime-table td {
        padding: 16px 12px;
        border-bottom: 1px solid #f5f5f5;
        vertical-align: middle;
    }

    .employee-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .employee-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #6750A4;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    .overtime-hours {
        background: #e3f2fd;
        color: #1565c0;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pending { background: #fff3e0; color: #f57c00; }
    .status-approved { background: #e8f5e8; color: #2e7d32; }
    .status-rejected { background: #ffebee; color: #c62828; }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f5f5f5;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 16px;
        border: none;
        cursor: pointer;
    }

    .btn-action:hover {
        background: #6750A4;
        color: white;
    }

    .btn-approve:hover { background: #4CAF50; }
    .btn-reject:hover { background: #f44336; }

    .text-center {
        text-align: center;
        padding: 40px;
        color: #666;
    }
</style>
@endpush

@push('scripts')
<script>
function approveOvertime(id) {
    if (confirm('Are you sure you want to approve this overtime request?')) {
        fetch(`/admin/overtime/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error approving overtime request');
            }
        });
    }
}

function rejectOvertime(id) {
    const reason = prompt('Please enter rejection reason:');
    if (reason) {
        fetch(`/admin/overtime/${id}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ rejection_reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error rejecting overtime request');
            }
        });
    }
}
</script>
@endpush