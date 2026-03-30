<?php

namespace App\Models;

use App\AttendanceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'student_id',
        'lecture_id',
        'check_in_time',
        'delay_minutes',
        'status',
    ];

    protected $casts = [
        'status' => AttendanceStatus::class,
        'check_in_time' => 'datetime',
    ];


    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function lecture(): BelongsTo
    {
        return $this->belongsTo(Lecture::class);
    }
}
