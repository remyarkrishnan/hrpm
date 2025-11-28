<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'file_path', 'file_name'];

    // Relation: Employee has many Attachments
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
