<?php

namespace App\Http\Controllers\Api\Student;

use App\Actions\StatisticsStudentAction;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticsAttendanceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StatisticsStudentAction $statisticsStudentAction): JsonResponse
    {
        $statistics = $statisticsStudentAction->execute(Auth::user());

        return response()->json([
            'data' => $statistics,
            'message'=> ResponseMessages::RETRIEVED->message()
        ]);
    }
}
