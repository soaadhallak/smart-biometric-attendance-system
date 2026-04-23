<?php

namespace App\Http\Controllers\Api\Student;

use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class DeleteAllEnrollmentsAndLecturesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
         DB::transaction(function () {
            Enrollment::query()->delete();
            Lecture::query()->delete();
        });

        return response()->json([
            'message' => ResponseMessages::DELETED->message(),
        ]);
    }
}
