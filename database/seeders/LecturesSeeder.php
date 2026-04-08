<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lecture;
use App\Models\User;
use App\Models\Year;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LecturesSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacher = User::firstOrCreate(
            ['email' => 'teacher2@example.com'],
            [
                'name' => 'Arabic Teacher Two',
                'password' => Hash::make('12345678'),
            ]
        );
        $teacher->assignRole('teacher');

        $courses = [];
        foreach ([
            'مبادئ برمجيات',
            'هندسة البيانات',
            'شبكات حاسوبية'
        ] as $courseName) {
            $courses[] = Course::firstOrCreate(
                ['name' => $courseName],
                ['teacher_id' => $teacher->id]
            );
        }


        $sundayThisWeek = Carbon::now()->startOfWeek(Carbon::SUNDAY);
        $sundayNextWeek = (clone $sundayThisWeek)->addWeek();

        foreach ($courses as $course) {
            Lecture::firstOrCreate([
                'course_id' => $course->id,
                'lecture_date' => $sundayThisWeek->toDateString(),
                'start_time' => '09:00:00',
                'end_time' => '10:30:00',
            ]);

            Lecture::firstOrCreate([
                'course_id' => $course->id,
                'lecture_date' => $sundayNextWeek->toDateString(),
                'start_time' => '09:00:00',
                'end_time' => '10:30:00',
            ]);
        }

        $student = User::whereHas('studentDetail', function ($q) {
            $q->where('university_number', '1212');
        })->first();

        if (! $student) {
            $student = User::create(
                [
                    'name' => 'Student 1212',
                    'password' => Hash::make('12345678'),
                ]
            );
            $student->assignRole('student');

            $year = Year::first();
            if ($year) {
                $student->studentDetail()->firstOrCreate([
                    'user_id' => $student->id,
                ], [
                    'university_number' => '12345678',
                    'year_id' => $year->id,
                ]);
            }
        }


        foreach ($courses as $course) {
            Enrollment::firstOrCreate([
                'student_id' => $student->id,
                'course_id' => $course->id,
            ]);
        }
    }
}
