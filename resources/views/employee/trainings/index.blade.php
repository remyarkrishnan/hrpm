@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.employee')

@section('title', __('employee/trainings/index.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Employee Dashboard')

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