<?php

namespace App\Data;

use App\Models\Enrollment;
use Mrmarchone\LaravelAutoCrud\Traits\HasModelAttributes;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class EnrollmentData extends Data
{
    use HasModelAttributes;

    protected static string $model = Enrollment::class;

    public function __construct(
        #[ArrayType()]
        public array $coursesIds,

        #[Exists('users', 'id')]
        public ?int $studentId,
    ) {}
}
