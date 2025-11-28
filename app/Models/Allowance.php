<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'allowance_name', 'percentage', 'amount'];

    // Relation: Employee has many Allowances
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
