<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Attendance;
use App\Models\StudentDetail;
use App\Models\Enrollment;
use App\Models\Major;
use App\Enums\AttendanceStatus;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSystemSeeder extends Seeder
{
    public function run()
    {
        $major = Major::firstOrCreate(['name' => 'هندسة برمجيات']);
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@university.edu'],
            [
                'name' => 'Dr. Ahmad Ali',
                'password' => Hash::make('password'),
            ]
        );

        $teacher->assignRole('teacher');
        $courses = collect(['البرمجة الغرضية', 'قواعد البيانات', 'ذكاء اصطناعي'])->map(function ($name) use ($teacher) {
            return Course::firstOrCreate(
                ['name' => $name],
                ['teacher_id' => $teacher->id]
            );
        });

        $studentsData = [
            ['name' => 'Samer Student', 'univ_id' => '12345678'],
        ];

        $students = collect();

        foreach ($studentsData as $data) {
            $user = User::firstOrCreate(
                ['name' => $data['name']],
                ['name' => $data['name'], 'password' => Hash::make('12345678')]
            );

            $user->assignRole('student');
            StudentDetail::firstOrCreate(
                ['university_number' => $data['univ_id']],
                [
                    'user_id' => $user->id,
                    'year_id' => 1,
                    'major_id' => $major->id,
                    'device_id' => 'SP1A.210812.016'
                ]
            );

            foreach ($courses as $course) {
                Enrollment::firstOrCreate([
                    'student_id' => $user->id,
                    'course_id' => $course->id
                ]);
            }
            $students->push($user);
        }

        $this->createLecturesAndAttendance($courses, $students);
    }

    private function createLecturesAndAttendance($courses, $students)
    {
        $startOfCurrentWeek = now()->startOfWeek(Carbon::SUNDAY);
        $startOfNextWeek = now()->startOfWeek(Carbon::SUNDAY)->addWeek();

        for ($i = 0; $i < 14; $i++) {
            $isNextWeek = $i >= 7;
            $daysToAdd = $i % 7;
            $date = ($isNextWeek ? $startOfNextWeek : $startOfCurrentWeek)->copy()->addDays($daysToAdd);

            $lecture = Lecture::create([
                'course_id' => $courses->random()->id,
                'lecture_date' => $date->format('Y-m-d'),
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
            ]);
            $lectureDateOnly = Carbon::parse($lecture->lecture_date)->format('Y-m-d');
            $endTime = Carbon::parse($lectureDateOnly . ' ' . $lecture->end_time);
            if ($endTime->isPast()) {
                foreach ($students as $student) {
                    $status = collect([
                        AttendanceStatus::PRESENT->value,
                        AttendanceStatus::LATE->value,
                        AttendanceStatus::ABSENT->value
                    ])->random();

                    Attendance::create([
                        'student_id' => $student->id,
                        'lecture_id' => $lecture->id,
                        'status' => $status,
                        'delay_minutes' => ($status === AttendanceStatus::LATE->value) ? rand(5, 30) : 0,
                        'check_in_time' => ($status !== AttendanceStatus::ABSENT->value) ? now() : null,
                    ]);
                }
            }
        }
    }
}
