<?php

namespace App\Http\Controllers\Api\Admin;

use App\Data\LectureData;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\LectureStoreRequest;
use App\Http\Requests\LectureUpdateRequest;
use App\Http\Resources\LectureResource;
use App\Models\Lecture;
use App\Services\LectureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class LectureController extends Controller
{
    public function __construct(
        protected LectureService $lectureService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $lectures = Lecture::with(['course.teacher'])->paginate(10);

        return LectureResource::collection($lectures)
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message(),
            ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LectureStoreRequest $request): JsonResponse
    {
        $lecture = $this->lectureService->store(LectureData::from($request->validated()));

        return LectureResource::make($lecture->load('course.teacher'))
            ->additional([
                'message' => ResponseMessages::CREATED->message(),
            ])->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lecture $lecture): LectureResource
    {
        return LectureResource::make($lecture->load('course.teacher'))
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message(),
            ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LectureUpdateRequest $request, Lecture $lecture): LectureResource
    {
        $lecture = $this->lectureService->update($lecture, LectureData::from($request->validated()));

        return LectureResource::make($lecture->load('course.teacher'))
            ->additional([
                'message' => ResponseMessages::UPDATED->message(),
            ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lecture $lecture): LectureResource
    {
        $lecture->delete();

        return LectureResource::make($lecture->load('course.teacher'))
            ->additional([
                'message' => ResponseMessages::DELETED->message(),
            ]);
    }
}
