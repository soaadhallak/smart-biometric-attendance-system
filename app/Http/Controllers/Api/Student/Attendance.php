<?php

namespace App\Http\Controllers\Api\Student;

use App\Data\AttendanceData;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceStoreRequest;
use App\Http\Resources\AttendanceResource;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class Attendance extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {}


    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttendanceStoreRequest $request): JsonResponse
    {
        $attendance = $this->attendanceService->store(Auth::user(), AttendanceData::from($request->validated()));

        return AttendanceResource::make($attendance->load(['student.studentDetail', 'lecture']))
            ->additional([
                'message' => ResponseMessages::CREATED->message()
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
