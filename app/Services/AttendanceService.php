<?php

namespace App\Services;

use App\Data\AttendanceData;
use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Lecture;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function store(User $student, AttendanceData $attendanceData): Attendance
    {
        return DB::transaction(function () use ($student, $attendanceData) {
            $attendance = $student->attendances()->create($attendanceData->onlyModelAttributes());

            return $attendance;
        });
    }
}

