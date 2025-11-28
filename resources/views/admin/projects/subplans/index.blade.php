@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.admin')

@section('title', __('projects.superplans.index.title', ['step' => $step->step_name, 'company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('projects.superplans.index.page_title'))

@section('content')
<div class="page-header">
    <div>
        <p><h4>{{ __('projects.superplans.index.step_label') }}: <strong>{{ $step->step_name }}</strong></h4></p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.subplans.create', $step->id) }}" class="btn-primary">
            <i class="material-icons">add</i>
            {{ __('projects.superplans.index.add_button') }}
        </a>
    </div>
</div>

<!-- Leave Requests Table -->
<div class="leave-table-card">
    <h3>{{ __('projects.superplans.index.table_title', ['step' => $step->step_name]) }}</h3>
    <div class="table-responsive">
        <table class="leave-table">
            <thead>
                <tr>
                    <th>{{ __('projects.superplans.index.table.index') }}</th>
                    <th>{{ __('projects.superplans.index.table.activity') }}</th>
                    <th>{{ __('projects.superplans.index.table.duration') }}</th>
                    <th>{{ __('projects.superplans.index.table.progress') }}</th>
                    <th>{{ __('projects.superplans.index.table.description') }}</th>
                    <th>{{ __('projects.superplans.index.table.view') }}</th>
                    <th width="130">{{ __('projects.superplans.index.table.actions') }}</th>
                </tr>
            </thead>
            @if($step->subplans->count() > 0)
                @foreach($step->subplans as $key => $subplan)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $subplan->activity_name }}</td>
                    <td>{{ $subplan->start_date?->format('d M') }} - {{ $subplan->end_date?->format('d M, Y') }}</td>
                    <td>{{ $subplan->progress_percentage }}%</td>
                    <td>{{ Str::limit($subplan->description, 50) }}</td>
                    <td> 
                        <a href="{{ route('admin.projects.resources.show', $project_id) }}" class="btn btn-primary">
                            <i class="fas fa-users me-1"></i> {{ __('projects.superplans.index.view_resources') }}
                        </a>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.subplans.edit', $subplan->id) }}" class="btn-action edit" title="{{ __('projects.superplans.index.edit_tooltip') }}">
                                <i class="material-icons">edit</i>
                            </a>
                            
                            <a href="{{ route('admin.subplans.resources.index', $subplan->id) }}" title="{{ __('projects.superplans.index.resources_tooltip') }}">
                                <button class="btn-action btn-assign">
                                    <i class="material-icons">group_add</i>
                                </button>
                            </a>
                            
                            <form action="{{ route('admin.subplans.destroy', $subplan->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn-action delete" onclick="return confirm('{{ __("projects.superplans.index.delete_confirm") }}');">
                                    <i class="material-icons">delete</i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center">
                        <h3>{{ __('projects.superplans.index.no_data') }}</h3>
                    </td>
                </tr>
            @endif 
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
        gap: 4px;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-action.view { background: #e3f2fd; color: #1565c0; }
    .btn-action.edit { background: #fff3e0; color: #f57c00; }
    .btn-action.delete { background: #ffebee; color: #c62828; }

    .btn-action:hover { transform: scale(1.1); }
    .btn-action i { font-size: 16px; }

    .text-center {
        text-align: center;
        padding: 40px;
        color: #666;
    }
</style>
@endpush

@push('scripts')
<script>
    async function deleteSubplan(id) {
        if (!confirm('{{ __("projects.superplans.index.delete_confirm_js") }}')) {
            return;
        }

        try {
            const response = await fetch(`/admin/subplans/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (data.success) {
                location.reload();
            } else {
                alert(data.message || '{{ __("projects.superplans.index.delete_error") }}');
            }
        } catch (error) {
            alert('{{ __("projects.superplans.index.delete_error") }}');
            console.error(error);
        }
    }
</script>
@endpush
