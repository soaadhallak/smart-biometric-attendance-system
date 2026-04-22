<?php

namespace App\Data;

use App\Models\StudentDetail;
use Mrmarchone\LaravelAutoCrud\Traits\HasModelAttributes;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class StudentData extends Data
{
    use HasModelAttributes;
    protected static string $model = StudentDetail::class;

    public function __construct(
        #[Max(10),Unique('student_details','university_number')]
        public ?string $universityNumber,
        #[Exists('years','id')]
        public ?int $yearId,
        #[Exists('majors','id')]
        public ?int $majorId,
        public ?string $deviceId = null
    ) {}
}
