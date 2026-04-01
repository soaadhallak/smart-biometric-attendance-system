<?php

namespace App\Rules;

use App\Models\Lecture;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckLectureInTodayRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $lecture = Lecture::findOrFail($value);
        
        if ($lecture->lecture_date->toDateString() !== now()->toDateString()) {
            $fail('Attendance can only be marked on the lecture date.');
        }        
    }
}
