<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EmployeeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'document_type',
        'document_name',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'is_mandatory',
        'is_verified',
        'uploaded_by',
        'verified_by',
        'verified_at',
        'notes',
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'file_size' => 'integer',
    ];

    /**
     * Document types
     */
    public static function getDocumentTypes()
    {
        return [
            'aadhar' => 'Aadhar Card',
            'pan' => 'PAN Card',
            'passport' => 'Passport',
            'driving_license' => 'Driving License',
            'voter_id' => 'Voter ID',
            'resume' => 'Resume/CV',
            'offer_letter' => 'Offer Letter',
            'joining_letter' => 'Joining Letter',
            'experience_letter' => 'Experience Letter',
            'education_certificate' => 'Education Certificate',
            'other' => 'Other Document',
        ];
    }

    /**
     * Employee relationship
     */
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Uploaded by relationship
     */
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Verified by relationship
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get document URL
     */
    public function getDocumentUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get document type label
     */
    public function getDocumentTypeLabelAttribute()
    {
        $types = self::getDocumentTypes();
        return $types[$this->document_type] ?? ucfirst(str_replace('_', ' ', $this->document_type));
    }

    /**
     * Check if document is an image
     */
    public function isImage()
    {
        return strpos($this->mime_type, 'image/') === 0;
    }

    /**
     * Check if document is a PDF
     */
    public function isPdf()
    {
        return $this->mime_type === 'application/pdf';
    }

    /**
     * Delete document file when model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($document) {
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }
        });
    }
}