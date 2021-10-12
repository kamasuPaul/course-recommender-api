<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultSubject extends Model
{
    use HasFactory;
    //declare scope for level
    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }
}
