@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.admin')

@section('title', __('project_locations.edit.title', ['location' => $projectLocation->location_name, 'company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('project_locations.edit.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.project-locations.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('project_locations.edit.back_button') }}
        </a>
    </div>
    <h2>{{ $projectLocation->location_name }}</h2>
    <p>{{ __('project_locations.edit.description') }}</p>
</div>

<form action="{{ route('admin.project-locations.update', $projectLocation) }}" method="POST" enctype="multipart/form-data" class="project-form">
    @csrf
    @method('PUT')

    <!-- Basic Project Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">info</i>
            {{ __('project_locations.edit.location_info') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="type">{{ __('project_locations.edit.fields.project') }} *</label>
                <select id="type" name="project_id" required>
                    <option value="">{{ __('global.select_option') }}</option>
                    @foreach($projects as $prj)
                        <option value="{{ $prj->id }}" {{ old('project_id', $projectLocation->project_id) === $prj->id ? 'selected' : '' }}>
                            {{ $prj->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="location_name">{{ __('project_locations.edit.fields.location_name') }} *</label>
                <input type="text" id="location_name" name="location_name" value="{{ old('location_name', $projectLocation->location_name) }}" required 
                       placeholder="{{ __('project_locations.edit.fields.location_name_placeholder') }}">
                @error('location_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="city">{{ __('project_locations.edit.fields.city') }} *</label>
                <input type="text" id="city" name="city" value="{{ old('city', $projectLocation->city) }}" required
                       placeholder="{{ __('project_locations.edit.fields.city_placeholder') }}">
                @error('city')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="state">{{ __('project_locations.edit.fields.state') }} *</label>
                <input type="text" id="state" name="state" value="{{ old('state', $projectLocation->state) }}" required
                       placeholder="{{ __('project_locations.edit.fields.state_placeholder') }}">
                @error('state')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="postal_code">{{ __('project_locations.edit.fields.pin_code') }} *</label>
                <input type="number" id="postal_code" name="postal_code" value="{{ old('postal_code', $projectLocation->postal_code) }}" required
                       placeholder="{{ __('project_locations.edit.fields.pin_code_placeholder') }}">
                @error('postal_code')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display:none">
                <label for="latitude">{{ __('project_locations.edit.fields.latitude') }}</label>
                <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $projectLocation->latitude) }}" 
                       placeholder="{{ __('project_locations.edit.fields.latitude_placeholder') }}">
                @error('latitude')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display:none">
                <label for="longitude">{{ __('project_locations.edit.fields.longitude') }}</label>
                <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $projectLocation->longitude) }}" 
                       placeholder="{{ __('project_locations.edit.fields.longitude_placeholder') }}">
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
            {{ __('project_locations.edit.geofence_title') }}
        </h3>

        <div class="form-group full-width">
            <label for="map-search">{{ __('project_locations.edit.fields.map_search') }}</label>
            <input id="map-search" type="text" placeholder="{{ __('project_locations.edit.map_search_placeholder') }}"
                   style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; margin-bottom:12px;">
            
            <div id="map" style="height:500px; width:100%; border-radius:12px; border:1px solid #ccc;"></div>
            
            <input type="hidden" name="geofence_coordinates" id="geofence_coordinates"
                   value="{{ old('geofence_coordinates', $projectLocation->geofence_coordinates) }}">
            
            <small class="form-help">{{ __('project_locations.edit.map_help') }}</small>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="material-icons">save</i>
            {{ __('project_locations.edit.update_button') }}
        </button>
        <a href="{{ route('admin.project-locations.show', $projectLocation) }}" class="btn-cancel">
            {{ __('project_locations.edit.cancel_button') }}
        </a>
        <button type="button" onclick="deleteProjectLocation({{ $projectLocation->id }})" class="btn-danger">
            <i class="material-icons">delete</i>
            {{ __('project_locations.edit.delete_button') }}
        </button>
    </div>
</form>
@endsection

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }
    .page-nav {
        margin-bottom: 16px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .btn-back, .btn-secondary {
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
    .btn-back:hover, .btn-secondary:hover { background: rgba(103, 80, 164, 0.08); }
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
        flex-wrap: wrap;
    }
    .btn-primary, .btn-cancel, .btn-danger {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
    }
    .btn-primary {
        background: #6750A4;
        color: white;
    }
    .btn-primary:hover { background: #5A4A94; }
    .btn-cancel {
        border: 2px solid #e0e0e0;
        color: #666;
        background: white;
    }
    .btn-cancel:hover {
        border-color: #666;
        color: #333;
    }
    .btn-danger {
        background: #d32f2f;
        color: white;
    }
    .btn-danger:hover { background: #b71c1c; }
    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-section { padding: 20px; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel, .btn-danger { width: 100%; justify-content: center; }
        .page-nav { flex-direction: column; }
    }
</style>
@endpush

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places,drawing,geometry"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let map;
    let drawingManager;
    let selectedPolygon = null;
    let marker = null;

    // Parse existing coordinates (if any)
    const existingCoordsJson = document.getElementById('geofence_coordinates').value;
    let existingCoords = null;
    try {
        existingCoords = existingCoordsJson ? JSON.parse(existingCoordsJson) : null;
    } catch(e) {
        console.error('Invalid JSON for existing geofence coordinates', e);
    }

    function initMap() {
        const defaultCenter = { lat: 23.8859, lng: 45.0792 }; // Saudi Arabia centre
        map = new google.maps.Map(document.getElementById("map"), {
            center: defaultCenter,
            zoom: 6,
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

        // Drawing Manager
        drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: null, // default no drawing mode
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

        // If existing coordinates, draw polygon
        if (existingCoords && Array.isArray(existingCoords)) {
            selectedPolygon = new google.maps.Polygon({
                paths: existingCoords,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                editable: true,
                map: map
            });
            // Fit map to polygon
            const bounds = new google.maps.LatLngBounds();
            existingCoords.forEach(coord => {
                bounds.extend(coord);
            });
            map.fitBounds(bounds);

            // Enable editing: on path change update hidden input
            google.maps.event.addListener(selectedPolygon.getPath(), 'set_at', updateCoordinates);
            google.maps.event.addListener(selectedPolygon.getPath(), 'insert_at', updateCoordinates);
            google.maps.event.addListener(selectedPolygon.getPath(), 'remove_at', updateCoordinates);
        }

        // On overlay complete (new draw)
        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
            if (selectedPolygon) {
                selectedPolygon.setMap(null);
            }
            selectedPolygon = event.overlay;
            drawingManager.setDrawingMode(null);

            // update hidden input
            updateCoordinates();

            // Also attach listeners for edit
            google.maps.event.addListener(selectedPolygon.getPath(), 'set_at', updateCoordinates);
            google.maps.event.addListener(selectedPolygon.getPath(), 'insert_at', updateCoordinates);
            google.maps.event.addListener(selectedPolygon.getPath(), 'remove_at', updateCoordinates);
        });

        function updateCoordinates() {
            const path = selectedPolygon.getPath();
            const coords = [];
            for (let i = 0; i < path.getLength(); i++) {
                const latLng = path.getAt(i);
                coords.push({ lat: latLng.lat(), lng: latLng.lng() });
            }
            document.getElementById('geofence_coordinates').value = JSON.stringify(coords);
        }

        // Add clear button
        const clearControlDiv = document.createElement("div");
        const clearButton = document.createElement("button");
        clearButton.innerHTML = "🧹 {{ __('project_locations.edit.clear_area') }}";
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

    if (typeof initMap === 'function') {
        initMap();
    }

    // Form submission loading state
    document.querySelector('.project-form').addEventListener('submit', function(e) {
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.innerHTML = '<i class="material-icons">hourglass_empty</i> {{ __("global.updating") }}...';
        submitButton.disabled = true;
    });
});

// Delete project location function
async function deleteProjectLocation(projectLocationId) {
    if (!confirm('{{ __("project_locations.edit.delete_confirm") }}')) {
        return;
    }

    const confirmation = prompt('{{ __("project_locations.edit.delete_confirm_text") }}');
    if (confirmation !== 'DELETE') {
        alert('{{ __("project_locations.edit.delete_cancelled") }}');
        return;
    }

    try {
        const response = await fetch(`/admin/project-locations/${projectLocationId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            alert('{{ __("project_locations.edit.delete_success") }}');
            window.location.href = '/admin/project-locations';
        } else {
            alert(data.message || '{{ __("project_locations.edit.delete_error") }}');
        }
    } catch (error) {
        alert('{{ __("project_locations.edit.delete_network_error") }}: ' + error.message);
        console.error(error);
    }
}
</script>
@endpush
