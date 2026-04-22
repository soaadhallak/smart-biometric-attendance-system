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
            'majorId' => ['required', 'exists:majors,id'],
            'password'=>['required', 'string', Rules\Password::default()],
            'deviceId' => ['required', 'string', 'unique:student_details,device_id'],
        ];
    }


}
