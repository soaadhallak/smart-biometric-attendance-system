<?php

namespace App\Actions;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Lecture;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsStudentAction
{
    public function execute(User $student): array
    {
        return DB::transaction(function () use ($student) {
            $startOfWeek = now()->startOfWeek(Carbon::SUNDAY);
            $endOfWeek = now()->endOfWeek(Carbon::SATURDAY);


            $courseIds = Enrollment::where('student_id', $student->id)->pluck('course_id');
            $weeklyLectures = Lecture::whereIn('course_id', $courseIds)
                ->whereBetween('lecture_date', [
                    $startOfWeek->format('Y-m-d'),
                    $endOfWeek->format('Y-m-d')
                ])
                ->get();

            $totalLecturesCount = $weeklyLectures->count();
            $givenLectures = $weeklyLectures->filter(function ($lecture) {
                $lectureDateOnly = Carbon::parse($lecture->lecture_date)->format('Y-m-d');
                $endTime = Carbon::parse($lectureDateOnly . ' ' . $lecture->end_time);

                return $endTime->isPast();
            });
            $lecturesGivenCount = $givenLectures->count();
            $lecturesRemainingCount = $totalLecturesCount - $lecturesGivenCount;

            $attendanceRecords = Attendance::where('student_id', $student->id)
                ->whereIn('lecture_id', $givenLectures->pluck('id'))
                ->get();

            $presenceCount = $attendanceRecords->where('status', AttendanceStatus::PRESENT->value)->count();
            $absenceCount = $attendanceRecords->where('status', AttendanceStatus::ABSENT->value)->count();

            $lateRecords = $attendanceRecords->where('status', AttendanceStatus::LATE->value);
            $totalDelayMinutes = $lateRecords->sum('delay_minutes');

            $averageDelay = $presenceCount > 0
                ? round($totalDelayMinutes / $presenceCount, 2)
                : 0;

            return [
                'totalLectures'     => $totalLecturesCount,
                'lecturesGiven'     => $lecturesGivenCount,
                'lecturesRemaining' => $lecturesRemainingCount,
                'presenceCount'     => $presenceCount,
                'absenceCount'      => $absenceCount,
                'averageDelayMin'  => $averageDelay,
            ];
        });
    }
}
