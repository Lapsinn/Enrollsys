<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['code', 'name', 'units'];

    public function enrollmentForms()
    {
        return $this->belongsToMany(EnrollmentForm::class, 'enrollment_subject');
    }
}
