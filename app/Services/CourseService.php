<?php

namespace App\Services;

use App\Data\CourseData;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseService
{
    public function store(CourseData $data): Course
    {
        return DB::transaction(function () use ($data) {
            $course = Course::create($data->onlyModelAttributes());

            return $course;
        });
    }

    public function update(Course $course, CourseData $data): Course
    {
        return DB::transaction(function () use ($course, $data) {
            tap($course)->update($data->onlyModelAttributes());

            return $course;
        });
    }

    public function index($day)
    {
        $courses = Course::getQuery()
            ->whereHas('enrollments', fn ($q) => $q->where('student_id', Auth::id()))
            ->whereHas('lectures', function ($q) {
                $q->whereBetween('lecture_date', [now()->startOfWeek(Carbon::SUNDAY), now()->endOfWeek(Carbon::SATURDAY)]);
            })
            ->with([
                'teacher',
                'lectures' => function ($q) use ($day) {
                    $q->whereBetween('lecture_date', [now()->startOfWeek(Carbon::SUNDAY), now()->endOfWeek(Carbon::SATURDAY)]);

                    if ($day && $day !== 'all') {
                        $dayNumber = Carbon::parse($day)->dayOfWeek + 1;
                        $q->whereRaw('DAYOFWEEK(lecture_date) = ?', [$dayNumber]);
                    }

                    $q->orderBy('lecture_date')->orderBy('start_time');
                },
            ])
            ->paginate(10)
            ->withQueryString();

        return $courses;
    }
}
