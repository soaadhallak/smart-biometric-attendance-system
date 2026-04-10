<?php

namespace App\Actions;

use App\Models\Course;
use App\Enums\AttendanceStatus;
use App\Models\Lecture;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GetAdminCoursesStatsAction
{
    public function execute()
    {
        $courseStats = DB::table('courses')
            ->leftJoin('lectures', 'courses.id', '=', 'lectures.course_id')
            ->leftJoin('attendances', 'lectures.id', '=', 'attendances.lecture_id')
            ->select([
                'courses.id',
                'courses.name',
                DB::raw('COUNT(DISTINCT lectures.id) as totalLectures'),
                DB::raw('SUM(CASE WHEN attendances.status = "' . AttendanceStatus::PRESENT->value . '" AND attendances.id IS NOT NULL THEN 1 ELSE 0 END) as totalPresence'),
                DB::raw('SUM(CASE WHEN attendances.status = "' . AttendanceStatus::ABSENT->value . '" THEN 1 ELSE 0 END) as totalAbsence'),
                DB::raw('ROUND(AVG(CASE WHEN attendances.status = "' . AttendanceStatus::LATE->value . '" THEN attendances.delay_minutes END), 2) as averageDelay')
            ])
            ->groupBy('courses.id', 'courses.name')
            ->get();

        $studentsPerYear = DB::table('years')
            ->leftJoin('student_details', 'years.id', '=', 'student_details.year_id')
            ->select([
                'years.name as year_name',
                DB::raw('COUNT(student_details.user_id) as totalStudents')
            ])
            ->groupBy('years.id', 'years.name')
            ->get();

        $quickStats = [
            'totalStudents' => DB::table('student_details')->count(),
            'totalCourses'  => Course::count(),
            'lecturesToday' => Lecture::whereDate('lecture_date', today())->count(),
        ];

        return [
            'courseStats'     => $courseStats,
            'studentsPerYear' => $studentsPerYear,
            'quickStats'      => $quickStats
        ];
    }
}
