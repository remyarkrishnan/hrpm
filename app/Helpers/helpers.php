<?php

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

if (!function_exists('uploadProfileImage')) {
    /**
     * Upload and resize profile image
     */
    function uploadProfileImage($file, $directory = 'profile-images')
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Resize image to 300x300
        $image = Image::make($file)->fit(300, 300);

        // Save to storage
        Storage::disk('public')->put($directory . '/' . $filename, $image->stream());

        return $filename;
    }
}

if (!function_exists('calculateDistance')) {
    /**
     * Calculate distance between two coordinates in meters using Haversine formula
     */
    function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Earth's radius in meters

        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);

        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLon = $lon2Rad - $lon1Rad;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}

if (!function_exists('isWithinGeofence')) {
    /**
     * Check if location is within geofence
     */
    function isWithinGeofence($userLat, $userLon, $centerLat, $centerLon, $radiusMeters)
    {
        $distance = calculateDistance($userLat, $userLon, $centerLat, $centerLon);
        return $distance <= $radiusMeters;
    }
}

if (!function_exists('calculateWorkHours')) {
    /**
     * Calculate work hours between check-in and check-out
     */
    function calculateWorkHours($checkIn, $checkOut)
    {
        if (!$checkIn || !$checkOut) {
            return 0;
        }

        $start = new \DateTime($checkIn);
        $end = new \DateTime($checkOut);
        $diff = $start->diff($end);

        return round($diff->h + ($diff->i / 60), 2);
    }
}