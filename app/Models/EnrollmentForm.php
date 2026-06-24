<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Subject;

class EnrollmentForm extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'middle_name',
        'birthdate',
        'sex',
        'applicant_type',
        'program',
        'year_level',
        'semester',
        'address',
        'contact_number',
        'emergency_contact',
        'last_school',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // app/Models/EnrollmentForm.php
    public function student() {
    return $this->belongsTo(User::class, 'user_id');
    }

    public function subjects() {
    // Ensure the pivot table name 'enrollment_subject' matches your migration
    return $this->belongsToMany(Subject::class, 'enrollment_subject');
    }
}