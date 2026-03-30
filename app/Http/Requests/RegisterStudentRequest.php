<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterStudentRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'universityNumber' => ['required', 'string', 'max:50', 'unique:student_details,university_number'],
            'yearId' => ['required', 'exists:years,id'],
            'password'=>['required', 'string', Rules\Password::default()],
            'fingerprintTemplate' => ['required', 'string'],
            'fingerprintIdentifier' => ['required', 'string', 'unique:student_details,fingerprint_identifier']
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->filled('fingerprintTemplate')) {
        $this->merge([
            'fingerprintIdentifier' => hash(
                'sha256',
                $this->input('fingerprintTemplate')
            ),
        ]);
    }
    }


}
