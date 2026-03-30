<?php

namespace App\Data;

use App\Models\Course;
use Mrmarchone\LaravelAutoCrud\Traits\HasModelAttributes;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;

class CourseData extends Data
{
    use HasModelAttributes;
    protected static string $model = Course::class;

    public function __construct(
        #[Max(255)]
        public ?string $name,
        #[Exists('users', 'id')]
        public ?int $teacherId
    ) {}
}
