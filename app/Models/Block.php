<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
    ];

    // A block can have many enrollments
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}