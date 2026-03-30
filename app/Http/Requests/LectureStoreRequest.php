<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LectureStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'courseId' => ['required', 'exists:courses,id'],
            'lectureDate' => ['required', 'date', 'after_or_equal:today'],
            'startTime' => ['required', 'date_format:H:i'],
            'endTime' => ['required', 'date_format:H:i', 'after:startTime'],
        ];
    }
}
