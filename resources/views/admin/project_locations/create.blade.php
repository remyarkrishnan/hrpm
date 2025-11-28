@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.admin')

@section('title', __('project_locations.create.title', ['company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('project_locations.create.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.project-locations.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('project_locations.create.back_button') }}
        </a>
    </div>
   
    <p>{{ __('project_locations.create.description') }}</p>
</div>

<form action="{{ route('admin.project-locations.store') }}" method="POST" enctype="multipart/form-data" class="project-form">
    @csrf

    <!-- Basic Project Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">info</i>
            {{ __('project_locations.create.location_info') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="type">{{ __('project_locations.create.fields.project') }} *</label>
                <select id="type" name="project_id" required>
                    <option value="">{{ __('global.select_option') }}</option>
                    @foreach($projects as $prj)
                        <option value="{{ $prj->id }}" {{ old('project_id') == $prj->id ? 'selected' : '' }}>
                            {{ $prj->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="name">{{ __('project_locations.create.fields.location_name') }} *</label>
                <input type="text" id="location_name" name="location_name" value="{{ old('location_name') }}" required 
                       placeholder="{{ __('project_locations.create.fields.location_name_placeholder') }}">
                @error('location_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="city">{{ __('project_locations.create.fields.city') }} *</label>
                <input type="text" id="city" name="city" value="{{ old('city') }}" required
                       placeholder="{{ __('project_locations.create.fields.city_placeholder') }}">
                @error('city')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="state">{{ __('project_locations.create.fields.state') }} *</label>
                <input type="text" id="state" name="state" value="{{ old('state') }}" required
                       placeholder="{{ __('project_locations.create.fields.state_placeholder') }}">
                @error('state')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="postal_code">{{ __('project_locations.create.fields.pin_code') }} *</label>
                <input type="number" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required
                       placeholder="{{ __('project_locations.create.fields.pin_code_placeholder') }}">
                @error('postal_code')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display:none">
                <label for="latitude">{{ __('project_locations.create.fields.latitude') }}</label>
                <input type="text" id="latitude" name="latitude" value="{{ old('latitude') }}" 
                       placeholder="{{ __('project_locations.create.fields.latitude_placeholder') }}">
                @error('latitude')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display:none">
                <label for="longitude">{{ __('project_locations.create.fields.longitude') }}</label>
                <input type="text" id="longitude" name="longitude" value="{{ old('longitude') }}" 
                       placeholder="{{ __('project_locations.create.fields.longitude_placeholder') }}">
                @error('longitude')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Geofence Section -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">map</i>
            {{ __('project_locations.create.geofence_title') }}
        </h3>

        <div class="form-group full-width">
            <label for="map-search">{{ __('project_locations.create.fields.map_search') }}</label>
            <input id="map-search" type="text" placeholder="{{ __('project_locations.create.map_search_placeholder') }}" 
                   style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; margin-bottom: 12px;">
            <div id="map" style="height: 500px; width: 100%; border-radius: 12px; border: 1px solid #ccc;"></div>
            <input type="hidden" name="geofence_coordinates" id="geofence_coordinates">
            <small class="form-help">{{ __('project_locations.create.map_help') }}</small>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="material-icons">save</i>
            {{ __('project_locations.create.create_button') }}
        </button>
        <a href="{{ route('admin.project-locations.index') }}" class="btn-cancel">
            {{ __('project_locations.create.cancel_button') }}
        </a>
    </div>
</form>
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
        font-size: 28px;
        font-weight: 500;
        color: #1C1B1F;
    }
    .page-header p {
        margin: 0;
        color: #666;
        font-size: 16px;
    }
    .project-form {
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
        font-size: 20px;
        font-weight: 500;
        color: #1C1B1F;
    }
    .section-title i { color: #6750A4; }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
    .form-group .error {
        color: #d32f2f;
        font-size: 12px;
        margin-top: 4px;
    }
    .form-help {
        color: #666;
        font-size: 12px;
        margin-top: 4px;
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
        .form-section { padding: 20px; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel { width: 100%; justify-content: center; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    document.querySelector('.project-form').addEventListener('submit', function(e) {
        // Show loading state
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.innerHTML = '<i class="material-icons">hourglass_empty</i> {{ __("global.creating") }}...';
        submitButton.disabled = true;
    });
});
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places,drawing,geometry"></script>

<script>
    let map;
    let drawingManager;
    let selectedPolygon = null;
    let marker = null;

    function initMap() {
        const defaultCenter = { lat: 23.8859, lng: 45.0792 }; // Saudi Arabia center
        map = new google.maps.Map(document.getElementById("map"), {
            center: defaultCenter,
            zoom: 5,
            zoomControl: true,
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_BOTTOM
            }
        });

        // Search Box
        const input = document.getElementById("map-search");
        const searchBox = new google.maps.places.SearchBox(input);

        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
        });

        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();
            if (places.length === 0) return;

            const place = places[0];
            if (place.geometry && place.geometry.location) {
                map.setCenter(place.geometry.location);
                map.setZoom(16);

                if (marker) {
                    marker.setMap(null);
                }

                marker = new google.maps.Marker({
                    map: map,
                    position: place.geometry.location
                });
            }
        });

        // Drawing Tool
        drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: google.maps.drawing.OverlayType.POLYGON,
            drawingControl: true,
            drawingControlOptions: {
                drawingModes: ['polygon']
            },
            polygonOptions: {
                editable: true,
                draggable: false
            }
        });

        drawingManager.setMap(map);

        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
            if (selectedPolygon) {
                selectedPolygon.setMap(null);
            }

            selectedPolygon = event.overlay;
            drawingManager.setDrawingMode(null);

            const path = selectedPolygon.getPath();
            const coordinates = [];

            for (let i = 0; i < path.getLength(); i++) {
                coordinates.push({
                    lat: path.getAt(i).lat(),
                    lng: path.getAt(i).lng()
                });
            }

            document.getElementById('geofence_coordinates').value = JSON.stringify(coordinates);
        });

        // Add Clear Button
        const clearControlDiv = document.createElement("div");
        const clearButton = document.createElement("button");
        clearButton.innerHTML = "🧹 {{ __('project_locations.create.clear_area') }}";
        clearButton.style.backgroundColor = "#fff";
        clearButton.style.border = "2px solid #ccc";
        clearButton.style.borderRadius = "4px";
        clearButton.style.padding = "8px 12px";
        clearButton.style.margin = "10px";
        clearButton.style.boxShadow = "0 2px 6px rgba(0,0,0,0.3)";
        clearButton.style.cursor = "pointer";
        clearButton.style.fontWeight = "bold";

        clearButton.addEventListener("click", (e) => {
            e.preventDefault();

            if (selectedPolygon) {
                selectedPolygon.setMap(null);
                selectedPolygon = null;
                document.getElementById('geofence_coordinates').value = '';
            }

            if (marker) {
                marker.setMap(null);
                marker = null;
            }
        });

        clearControlDiv.appendChild(clearButton);
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(clearControlDiv);
    }

    window.onload = function() {
        if (typeof initMap === 'function') {
            initMap();
        }
    };
</script>
@endpush
