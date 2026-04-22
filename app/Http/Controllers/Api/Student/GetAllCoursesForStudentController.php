<?php

namespace App\Http\Controllers\Api\Student;

use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetAllCoursesForStudentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $courses = Course::where('major_id', Auth::user()->studentDetail->major_id)
            ->with('teacher', 'major')
            ->get();

        return CourseResource::collection($courses)
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message()
            ]);    
    }
}
