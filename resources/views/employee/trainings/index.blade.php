@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.employee')

@section('title', __('employee/trainings/index.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee/trainings/index.title'))

@section('content')
<div class="page-header">
    <div>
        <h2>{{ __('employee/trainings/index.title') }}</h2>
        <p>{{ __('employee/trainings/index.subtitle') }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('employee.trainings.create') }}" class="btn-primary">
            <i class="material-icons">add</i>
            {{ __('employee/trainings/common.actions.apply') }}
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card pending">
        <div class="stat-icon">
            <i class="material-icons">pending</i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalPendingTraining }}</h3>
            <p>{{ __('employee/trainings/index.stats.pending') }}</p>
        </div>
    </div>

    <div class="stat-card approved">
        <div class="stat-icon">
            <i class="material-icons">check_circle</i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalApprovedTraining }}</h3>
            <p>{{ __('employee/trainings/index.stats.approved') }}</p>
        </div>
    </div>
</div>

<div class="leave-table-card">
    <h3>{{ __('employee/trainings/index.title') }}</h3>
    <div class="table-responsive">
        <table class="leave-table">
            <thead>
                <tr>
                    <th>{{ __('employee/trainings/common.labels.name') }}</th>
                    <th>{{ __('employee/trainings/common.labels.location') }}</th>
                    <th>{{ __('employee/trainings/common.labels.duration') }}</th>
                    <th>{{ __('employee/trainings/common.labels.benefit') }}</th>
                    <th>{{ __('employee/trainings/common.labels.applied_date') }}</th>
                    <th>{{ __('employee/trainings/common.labels.status') }}</th>
                    <th>{{ __('employee/trainings/common.labels.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainings as $training)
                <tr>
                    <td>
                        <span class="leave-type">{{ $training->name }}</span>
                    </td>
                    <td>
                        <div class="leave-duration">
                            <strong>{{ $training->location }}</strong>
                        </div>
                    </td>
                    <td>
                        <div class="leave-duration">
                            <strong>{{ $training->duration }}</strong>
                        </div>
                    </td>
                    <td>
                        <small>{{ $training->benefit }}</small>
                    </td>
                    <td>{{ $training->created_at }}</td>
                    <td>
                        <span class="status-badge status-{{ $training->status }}">
                            {{ ucfirst($training->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('employee.trainings.show', $training->id) }}" class="btn-action">
                                <i class="material-icons">visibility</i>
                            </a>
                            @if($training->status === 'pending')
                                <button class="btn-action btn-reject" onclick="deleteTraining({{ $training->id }})">
                                    <i class="material-icons">close</i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">{{ __('employee/trainings/index.empty') }}</td>
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
    .stat-card.on-leave .stat-icon { background: #2196F3; }
    .stat-card.balance .stat-icon { background: #9C27B0; }

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

    .leave-table-card {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .leave-table-card h3 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .leave-table {
        width: 100%;
        border-collapse: collapse;
    }

    .leave-table th {
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #f0f0f0;
        font-weight: 600;
        color: #333;
    }

    .leave-table td {
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

    .leave-type {
        padding: 4px 12px;
        background: #f0f0f0;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .leave-duration strong {
        display: block;
        margin-bottom: 2px;
    }

    .leave-duration small {
        color: #666;
        font-size: 12px;
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
function approveLeave(id) {
    if (confirm('Are you sure you want to approve this loans request?')) {
        fetch(`/admin/loans/${id}/approve`, {
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
                alert('Error approving loan request');
            }
        });
    }
}

function rejectLoan(id) {
    const reason = prompt('Please enter rejection reason:');
    if (reason) {
        fetch(`/admin/loans/${id}/reject`, {
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
                alert('Error rejecting loan request');
            }
        });
    }
}

function deleteTraining() {
    if (confirm('Are you sure you want to delete this training request?\n\nThis action cannot be undone.')) {
        fetch(`/employee/trainings/{{ $training->id ?? '' }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Training request deleted successfully');
                window.location.href = '/employee/trainings';
            } else {
                alert('Error deleting training request');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting training request');
        });
    }
}
</script>
@endpush