<?php

namespace App\Http\Controllers\Api\Student;

use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\LectureFilterRequest;
use App\Http\Resources\CourseResource;
use App\Services\CourseService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetAllStudentLecturesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LectureFilterRequest $request, CourseService $courseService): AnonymousResourceCollection
    {
        $day = $request->input('filter')['day'] ?? null;
        $courses = $courseService->index($day);

        return CourseResource::collection($courses)
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message(),
            ]);
    }
}
