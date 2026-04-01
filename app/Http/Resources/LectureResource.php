<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LectureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $actualDate = Carbon::parse($this->getRawOriginal('lecture_date'))
            ->setTimeFromTimeString($this->getRawOriginal('start_time'));

        return [
            'id' => $this->id,
            'course' => CourseResource::make($this->whenLoaded('course')),
            'lectureDateAndTime' => $actualDate->format('l h:i A'),
            'endTime' => Carbon::parse($this->end_time)->format('h:i A'),
        ];
    }
}
