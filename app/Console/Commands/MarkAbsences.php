<?php

namespace App\Console\Commands;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Lecture;
use Illuminate\Console\Command;
use Carbon\Carbon;

class MarkAbsences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:mark-absences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark absent students for lectures that have ended';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $endedLectures = Lecture::whereRaw("CONCAT(lecture_date, ' ', end_time) < ?", [$now->toDateTimeString()])
            ->with('course.enrollments.student')
            ->get();

        foreach ($endedLectures as $lecture) {
            $this->info("Processing lecture: {$lecture->id} - {$lecture->course->name}");

            $enrolledStudents = $lecture->course->enrollments->pluck('student');

            foreach ($enrolledStudents as $student) {
                $existingAttendance = Attendance::where('student_id', $student->id)
                    ->where('lecture_id', $lecture->id)
                    ->first();

                if (!$existingAttendance) {
                    Attendance::create([
                        'student_id' => $student->id,
                        'lecture_id' => $lecture->id,
                        'check_in_time' => null,
                        'delay_minutes' => 0,
                        'status' => AttendanceStatus::ABSENT,
                    ]);

                    $this->line("Marked absent: Student {$student->id} for lecture {$lecture->id}");
                }
            }
        }

        $this->info('Absences marked successfully.');
    }
}