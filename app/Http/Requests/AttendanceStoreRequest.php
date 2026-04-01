<?php

namespace App\Http\Requests;

use App\Enums\AttendanceStatus;
use App\Models\Lecture;
use App\Rules\CheckLectureInTodayRule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AttendanceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lectureId' => ['required', 'integer', 'exists:lectures,id', new CheckLectureInTodayRule()],
            'checkInTime' => ['required', 'date_format:Y-m-d H:i:s'],
            'status' => ['nullable'],
            'delayMinutes' => ['nullable'],
        ];
    }

    protected function prepareForValidation()
    {
        $lectureId = $this->input('lectureId');
        $lecture = Lecture::findOrFail($lectureId);

        if ($lecture) {
            $checkInTime = $this->input('checkInTime');
            if ($checkInTime) {
                $checkIn = Carbon::parse($checkInTime);
                $lectureStart = Carbon::parse($lecture->lecture_date->format('Y-m-d') . ' ' . $lecture->start_time);

                $delayMinutes = max(0, $lectureStart->diffInMinutes($checkIn, false));

                if ($delayMinutes == 0) {
                    $status = AttendanceStatus::PRESENT;
                    $delayMinutes = 0;
                } else {
                    $status = AttendanceStatus::LATE;
                }

                $this->merge([
                    'delayMinutes' => $delayMinutes,
                    'status' => $status->value,
                ]);
            }
        }
    }
}
