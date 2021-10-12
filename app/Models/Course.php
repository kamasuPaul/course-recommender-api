<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $guarded = [];
    //cast the essential_subjects, relevant_subjects and other_subjects to array
    protected $casts = [
        'essential_subjects' => 'array',
        'relevant_subjects' => 'array',
        'desirable_subjects' => 'array'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
