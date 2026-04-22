<?php

namespace App\Http\Controllers\Api\Admin;

use App\Data\CourseData;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseStoreRequest;
use App\Http\Requests\CourseUpdateRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    public function __construct(
        protected CourseService $courseService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        $courses = Course::with(['teacher.roles'])->get();

        return CourseResource::collection($courses)
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message(),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseStoreRequest $request): JsonResponse
    {
        $course = $this->courseService->store(CourseData::from($request->validated()));

        return CourseResource::make($course->load('teacher.roles', 'major'))
            ->additional([
                'message' => ResponseMessages::CREATED->message(),
            ])->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course): CourseResource
    {
        return CourseResource::make($course->load('teacher.roles', 'major'))
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message(),
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseUpdateRequest $request, Course $course): CourseResource
    {
        $course = $this->courseService->update($course, CourseData::from($request->validated()));

        return CourseResource::make($course->load('teacher.roles', 'major'))
            ->additional([
                'message' => ResponseMessages::UPDATED->message(),
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): CourseResource
    {
        $course->delete();

        return CourseResource::make($course->load('teacher.roles'))
            ->additional([
                'message' => ResponseMessages::DELETED->message(),
            ]);
    }
}
