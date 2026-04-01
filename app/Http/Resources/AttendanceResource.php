<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student' => UserResource::make($this->whenLoaded('student')),
            'lecture' => LectureResource::make($this->whenLoaded('lecture')),
            'checkInTime' => $this->check_in_time->format('Y-m-d H:i:s'),
            'delayMinutes' => $this->delay_minutes,
            'status' => $this->status?->value ?? $this->status,
        ];
    }
}
