<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\GetAdminCoursesStatsAction;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminCourseStatsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GetAdminCoursesStatsAction $action): JsonResponse
    {
        $stats = $action->execute();

        return response()->json([
            'data'   => [
                'charts' => [
                    'courses' => $stats['courseStats'],
                    'years'   => $stats['studentsPerYear'],
                ],
                'counters' => $stats['quickStats']
            ],
            'message' => ResponseMessages::RETRIEVED->message(),
        ]);
    }
}
