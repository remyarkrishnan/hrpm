@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.employee')

@section('title', __('employee.attendance.create.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee.attendance.create.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('employee.attendance.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('employee.attendance.create.back_to_attendance') }}
        </a>
    </div>
    <h2>{{ __('employee.attendance.create.header_title') }}</h2>
    <p style="display:none">{{ __('employee.attendance.create.add_record') }}</p>
</div>

<div class="form-container">
   <form action="{{ route('employee.attendance.store') }}" method="POST" enctype="multipart/form-data" class="attendance-form">
    @csrf
        <div class="form-section" style="display:none">
            <h3 class="section-title">
                <i class="material-icons">person</i>
                {{ __('employee.attendance.create.date_section_title') }}
            </h3>

            <div class="form-grid" >
                <div class="form-group">
                    <label for="date">{{ __('employee.attendance.create.date_label') }} *</label>
                    <input type="date" id="date" name="date" value="{{ date('Y-m-d') }}" >
                </div>
            </div>
        </div>

        <div class="form-section" style="display:none">
            <h3 class="section-title">
                <i class="material-icons">schedule</i>
                {{ __('employee.attendance.create.time_section_title') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="check_in">{{ __('employee.attendance.create.check_in_label') }} *</label>
                    <input type="time" id="check_in" name="check_in" >
                </div>

                <div class="form-group">
                    <label for="check_out">{{ __('employee.attendance.create.check_out_label') }}</label>
                    <input type="time" id="check_out" name="check_out">
                </div>

                <div class="form-group">
                    <label for="status">{{ __('employee.attendance.create.status_label') }} *</label>
                    <select id="status" name="status" required>
                        <option value="present">{{ __('employee.attendance.create.status.present') }}</option>
                        <option value="absent">{{ __('employee.attendance.create.status.absent') }}</option>
                        <option value="late">{{ __('employee.attendance.create.status.late') }}</option>
                        <option value="half_day">{{ __('employee.attendance.create.status.half_day') }}</option>
                        <option value="work_from_home">{{ __('employee.attendance.create.status.work_from_home') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="break_duration">{{ __('employee.attendance.create.break_duration_label') }}</label>
                    <input type="number" id="break_duration" name="break_duration" placeholder="{{ __('employee.attendance.create.break_duration_placeholder') }}" min="0">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">location_on</i>
                {{ __('employee.attendance.create.location_section_title') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="project">{{ __('employee.attendance.create.project_label') }}</label>
                    <select id="project" name="project_id" {{ $disabled ? 'disabled' : '' }}>
                        <option value="">{{ __('employee.attendance.create.project_select') }}</option>
                    @foreach($projects as $prj)
                    <option value="{{ $prj->id }}" {{ (old('project_id') == $prj->id || (isset($attendance) && $attendance->project_id == $prj->id)) ? 'selected' : '' }}>
                        {{ $prj->name }}
                    </option>
                    @endforeach    
                    </select>
                </div>

                <div class="form-group">
                    <label for="location">{{ __('employee.attendance.create.location_label') }}</label>
                    <select id="project_location_id" name="project_location_id" {{ $disabled ? 'disabled' : '' }}>
                     @if($action === 'checkin')
                    <option value="">{{ __('employee.attendance.create.location_select') }}</option>
                    @else
                        @if($selectedLocation)
                            <option value="{{ $selectedLocation }}" selected>{{ $selectedLocation }}</option>
                        @endif
                    @endif
                    </select>
                     @if($disabled)
                        <input type="hidden" name="project_location_id" value="{{ $selectedLocationid }}">
                    @endif
                </div>

                <div class="form-group">
                   <label for="gps">
            {{ __('employee.attendance.create.gps_label') }}
            <button type="button" id="gpsBtn" class="btn btn-primary btn-sm ml-2" onclick="enableGPS()">
                {{ __('employee.attendance.create.gps_enable') }}
            </button>
        </label>
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
                </div>

                <div class="form-group">
                    <label for="project">{{ __('employee.attendance.create.photo_label') }}</label>
                    <input type="file" id="location_image" name="location_image" accept="image/*">
                </div>
            </div>
        </div>

        <div class="form-section" style="display:none">
            <h3 class="section-title">
                <i class="material-icons">note</i>
                {{ __('employee.attendance.create.additional_section_title') }}
            </h3>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="notes">{{ __('employee.attendance.create.notes_label') }}</label>
                    <textarea id="notes" name="notes" rows="3" 
                              placeholder="{{ __('employee.attendance.create.notes_placeholder') }}"></textarea>
                </div>

                <div class="form-group">
                    <label for="overtime_hours">{{ __('employee.attendance.create.overtime_label') }}</label>
                    <input type="number" id="overtime_hours" name="overtime_hours" 
                           step="0.5" min="0" placeholder="{{ __('employee.attendance.create.overtime_placeholder') }}">
                </div>
            </div>
        </div>

        <div class="form-actions">
        @if($action === 'checkin')
        <button type="submit" class="btn-primary" style="display:inline-block;">
            <i class="material-icons">check_circle</i> {{ __('employee.attendance.create.checkin_button') }}
        </button>
        @elseif($action === 'checkout')
            <button type="submit" class="btn-primary" style="display:inline-block;">
                <i class="material-icons">exit_to_app</i> {{ __('employee.attendance.create.checkout_button') }}
            </button>
        @elseif($action === 'checked_out')
            <button type="button" class="btn btn-secondary btn-lg w-50" disabled title="{{ __('employee.attendance.create.checked_out_title') }}">
                <i class="bi bi-check-circle"></i> {{ __('employee.attendance.create.checked_out_button') }}
            </button>
        @endif
            <button type="submit" class="btn-primary" style="display:none">
                <i class="material-icons">save</i>
                {{ __('employee.attendance.create.save_button') }}
            </button>
            <a href="{{ route('employee.attendance.index') }}" class="btn-cancel">
                {{ __('employee.attendance.create.cancel_button') }}
            </a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }

    .page-nav { margin-bottom: 16px; }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #6750A4;
        text-decoration: none;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 6px;
        transition: background 0.2s;
    }

    .btn-back:hover { background: rgba(103, 80, 164, 0.08); }

    .page-header h2 {
        margin: 0 0 8px 0;
        font-size: 24px;
        font-weight: 500;
    }

    .page-header p {
        margin: 0;
        color: #666;
    }

    .form-container {
        max-width: 800px;
    }

    .attendance-form {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .form-section {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0 0 24px 0;
        font-size: 18px;
        font-weight: 500;
        color: #1C1B1F;
    }

    .section-title i { color: #6750A4; }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width { grid-column: 1 / -1; }

    .form-group label {
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s;
        background: white;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #6750A4;
    }

    .form-actions {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        padding-top: 24px;
        border-top: 1px solid #e0e0e0;
    }

    .btn-primary {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #6750A4;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s;
    }

    .btn-primary:hover { background: #5A4A94; }

    .btn-cancel {
        padding: 12px 24px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        text-decoration: none;
        color: #666;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        border-color: #666;
        color: #333;
    }

    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel { width: 100%; justify-content: center; }
    }
</style>
@endpush

@push('scripts')
<script>
function enableGPS() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Set values to hidden fields
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                alert(`{{ __('employee.attendance.create.gps_success') }}:\n{{ __('employee.attendance.create.gps_latitude') }}: ${lat}\n{{ __('employee.attendance.create.gps_longitude') }}: ${lng}`);
            },
            function (error) {
                alert("{{ __('employee.attendance.create.gps_error') }}" + error.message);
            }
        );
    } else {
        alert("{{ __('employee.attendance.create.gps_not_supported') }}");
    }
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const projectSelect = document.getElementById('project');
    const locationSelect = document.getElementById('project_location_id');

    projectSelect.addEventListener('change', function () {
        const projectId = this.value;

        // Clear previous options
        locationSelect.innerHTML = '<option value="">{{ __('employee.attendance.create.location_select') }}</option>';

        if (!projectId) return;

        fetch(`/admin/projects/${projectId}/locations`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.locations.length > 0) {
                    data.locations.forEach(loc => {
                        const option = document.createElement('option');
                        option.value = loc.id;
                        option.textContent = loc.name;
                        locationSelect.appendChild(option);
                    });
                } else {
                    const opt = document.createElement('option');
                    opt.textContent = '{{ __('employee.attendance.create.no_locations') }}';
                    locationSelect.appendChild(opt);
                }
            })
            .catch(error => {
                console.error('Error fetching locations:', error);
            });
    });
});
</script>
@endpush
