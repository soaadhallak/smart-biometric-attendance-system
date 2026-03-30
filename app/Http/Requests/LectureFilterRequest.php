<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LectureFilterRequest extends FormRequest
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
            'filter' => ['sometimes', 'array'],
            'filter.day' => ['sometimes', 'string'],
            'filter.search' => ['sometimes', 'string', 'max:100'],
            'perPage' => ['sometimes', 'integer', 'min:1','max:10'],
        ];
    }
}
