<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = ['student_name', 'email', 'course', 'block_id'];

    public function block()
    {
        return $this->belongsTo(Block::class);
    }
}
