<?php

namespace App\Data;

use App\Models\Lecture;
use Mrmarchone\LaravelAutoCrud\Traits\HasModelAttributes;
use Spatie\LaravelData\Attributes\Validation\After;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class LectureData extends Data
{
    use HasModelAttributes;

    protected static string $model = Lecture::class;

    public function __construct(
        #[Exists('courses', 'id')]
        public ?int $courseId,
        #[Date, DateFormat('Y-m-d')]
        public ?string $lectureDate,
        #[DateFormat('H:i')]
        public ?string $startTime,
        #[DateFormat('H:i'), After('startTime')]
        public ?string $endTime,
    ) {}
}
