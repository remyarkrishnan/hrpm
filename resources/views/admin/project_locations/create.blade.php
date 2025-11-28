@extends('layouts.admin')

@section('title', 'Create Project Location - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Create New Project Location')

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.project-locations.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            Back to Project Locations
        </a>
    </div>
   
    <p>Add a new project locations</p>
</div>

<form action="{{ route('admin.project-locations.store') }}" method="POST" enctype="multipart/form-data" class="project-form">
    @csrf

    <!-- Basic Project Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">info</i>
            Loation Information
        </h3>

        <div class="form-grid">


        <div class="form-group">
                <label for="type">Project  *</label>
                <select id="type" name="project_id" required>
                    <option value="">Select Project</option>
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
                <label for="name">Location Name *</label>
                <input type="text" id="location_name" name="location_name" value="{{ old('location_name') }}" required 
                       placeholder="">
                @error('location_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="city">City *</label>
                <input type="text" id="city" name="city" value="{{ old('city') }}" required
                       placeholder="">
                @error('city')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="type">State *</label>
                <input type="text" id="state" name="state" value="{{ old('state') }}" required
                       placeholder="">
                @error('state')
                    <span class="error">{{ $message }}</span>
                @enderror
               
            </div>

            <div class="form-group">
                <label for="type">PIN Code *</label>
                <input type="number" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required
                       placeholder="">
                @error('postal_code')
                    <span class="error">{{ $message }}</span>
                @enderror
               
            </div>

            <div class="form-group"  style="display:none">
                <label for="type">Latitude </label>
                <input type="text" id="latitude" name="latitude" value="{{ old('latitude') }}" 
                       placeholder="">
                @error('latitude')
                    <span class="error">{{ $message }}</span>
                @enderror
               
            </div>

            <div class="form-group"  style="display:none">
                <label for="type">Longitude </label>
                <input type="text" id="longitude" name="longitude" value="{{ old('longitude') }}" 
                       placeholder="">
                @error('longitude')
                    <span class="error">{{ $message }}</span>
                @enderror
               
            </div>

           
        </div>
    </div>

    <!-- New Section: Draw Geofence -->
<div class="form-section">
    <h3 class="section-title">
        <i class="material-icons">map</i>
        Define Geofence Area
    </h3>

   <div class="form-group full-width">
    <label for="map-search">Search Location</label>
    <input id="map-search" type="text" placeholder="Search for a location..." 
           style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; margin-bottom: 12px;">
    <div id="map" style="height: 500px; width: 100%; border-radius: 12px; border: 1px solid #ccc;"></div>
    <input type="hidden" name="geofence_coordinates" id="geofence_coordinates">
    <small class="form-help">Search a place and draw a polygon to define the project boundary.</small>
</div>

</div>


    <!-- Submit Buttons -->
    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="material-icons">save</i>
            Create Project Location
        </button>
        <a href="{{ route('admin.project-locations.index') }}" class="btn-cancel">
            Cancel
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

    /* Workflow Info Styles */
    .workflow-info {
        background: #f8f9fa;
        padding: 24px;
        border-radius: 12px;
        border-left: 4px solid #6750A4;
    }

    .workflow-description {
        margin: 0 0 20px 0;
        color: #333;
        line-height: 1.5;
    }

    .workflow-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
    }

    .workflow-step {
        background: white;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #6750A4;
        text-align: center;
        border: 1px solid #e0e0e0;
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
        .workflow-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const projectCodeInput = document.getElementById('project_code');
    const typeSelect = document.getElementById('type');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('expected_end_date');

    // Auto-generate project code based on name and type
    function generateProjectCode() {
        const name = nameInput.value;
        const type = typeSelect.value;
        const year = new Date().getFullYear();

        if (name && type) {
            const nameCode = name.substring(0, 3).toUpperCase();
            const typeCode = type.substring(0, 3).toUpperCase();
            const randomNum = Math.floor(Math.random() * 100).toString().padStart(2, '0');

            projectCodeInput.value = `${typeCode}-${nameCode}-${year}-${randomNum}`;
        }
    }

    nameInput.addEventListener('blur', generateProjectCode);
    typeSelect.addEventListener('change', generateProjectCode);

    // Set minimum end date based on start date
    startDateInput.addEventListener('change', function() {
        const startDate = new Date(this.value);
        const minEndDate = new Date(startDate);
        minEndDate.setDate(minEndDate.getDate() + 30); // Minimum 30 days

        endDateInput.min = minEndDate.toISOString().split('T')[0];

        if (endDateInput.value && new Date(endDateInput.value) <= startDate) {
            endDateInput.value = minEndDate.toISOString().split('T')[0];
        }
    });

    // Form validation
    document.querySelector('.project-form').addEventListener('submit', function(e) {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (endDate <= startDate) {
            e.preventDefault();
            alert('Expected completion date must be after start date');
            return;
        }

        // Show loading state
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.innerHTML = '<i class="material-icons">hourglass_empty</i> Creating Project...';
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
            zoomControl: true, // ✅ Enable zoom control
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_BOTTOM // Change position if needed
            }
        });

        // Search Box
        const input = document.getElementById("map-search");
        const searchBox = new google.maps.places.SearchBox(input);

        // Bias search results towards current map's viewport.
        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
        });

        // Listen for place selection
        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();

            if (places.length === 0) return;

            const place = places[0];

            // Center map to selected place
            if (place.geometry && place.geometry.location) {
                map.setCenter(place.geometry.location);
                map.setZoom(16);

                // Drop a marker
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

        // Capture polygon coordinates
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
        clearButton.innerHTML = "🧹 Clear Area";
        clearButton.style.backgroundColor = "#fff";
        clearButton.style.border = "2px solid #ccc";
        clearButton.style.borderRadius = "4px";
        clearButton.style.padding = "8px 12px";
        clearButton.style.margin = "10px";
        clearButton.style.boxShadow = "0 2px 6px rgba(0,0,0,0.3)";
        clearButton.style.cursor = "pointer";
        clearButton.style.fontWeight = "bold";

        clearButton.addEventListener("click", (e) => {
            e.preventDefault();  // Prevent form reset or page reload

            if (selectedPolygon) {
                selectedPolygon.setMap(null);  // Remove polygon from map
                selectedPolygon = null;
                document.getElementById('geofence_coordinates').value = '';  // Clear hidden input value
            }

            // Optionally, you can clear the marker as well
            if (marker) {
                marker.setMap(null);  // Remove marker from map
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
