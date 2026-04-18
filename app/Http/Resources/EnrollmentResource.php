<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'studentId' => $this->student_id,
            'courseId'  => $this->course_id,
            'course'     => CourseResource::make($this->whenLoaded('course')),
            'student'    => UserResource::make($this->whenLoaded('student'))
        ];
    }
}
