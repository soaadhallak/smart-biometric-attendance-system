<?php

namespace App\Http\Controllers\Api\Student;

use App\Actions\AddEnrollementsForStudentAction;
use App\Data\EnrollmentData;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentsRequest;
use App\Http\Resources\EnrollmentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AddEnrollmentsForStudentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreEnrollmentsRequest $request, AddEnrollementsForStudentAction $addEnrollementsForStudentAction): JsonResponse
    {
        $enrollments = $addEnrollementsForStudentAction->execute(Auth::user(), EnrollmentData::from($request->validated()));

        return EnrollmentResource::collection($enrollments)
            ->additional([
                'message' => ResponseMessages::CREATED->message()
            ])
            ->response()
            ->setStatusCode(201);
    }
}
