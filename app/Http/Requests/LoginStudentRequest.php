<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginStudentRequest extends FormRequest
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
            'universityNumber' => ['required_without:fingerprintTemplate', 'string'],
            'password' => ['required_with:universityNumber', 'string'],
            'fingerprintTemplate' => ['required_without:universityNumber'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

        $hasPasswordLogin =
            !empty($this->input('universityNumber')) &&
            !empty($this->input('password'));

        $hasFingerprintLogin =
            !empty($this->input('fingerprintTemplate'));

        if (!$hasPasswordLogin && !$hasFingerprintLogin) {
            $validator->errors()->add(
                'login',
                'You must provide university number & password or fingerprint.'
            );
        }
        });
    }
}
