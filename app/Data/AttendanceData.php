<?php

namespace App\Data;

use App\Models\Attendance;
use App\Enums\AttendanceStatus;
use Mrmarchone\LaravelAutoCrud\Traits\HasModelAttributes;
use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Data;

class AttendanceData extends Data
{
    use HasModelAttributes;
    protected static string $model = Attendance::class;

    public function __construct(
        #[Exists('users', 'id')]
        public ?int $studentId,
        #[Exists('lectures', 'id')]
        public ?int $lectureId,
        #[DateFormat('Y-m-d H:i:s')]
        public ?string $checkInTime,
        public ?int $delayMinutes,
        #[In([AttendanceStatus::PRESENT->value, AttendanceStatus::LATE->value, AttendanceStatus::ABSENT->value])]
        public ?string $status,
    ) {}
}
