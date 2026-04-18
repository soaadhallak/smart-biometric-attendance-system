<?php
namespace App\Actions;

use App\Models\User;
use App\Models\Enrollment;
use App\Data\EnrollmentData;
use Illuminate\Database\Eloquent\Collection;

class AddEnrollementsForStudentAction
{
    public function execute(User $student, EnrollmentData $data): Collection
    {
        $changes = $student->courses()->syncWithoutDetaching($data->coursesIds);
        $newIds = $changes['attached'];

        return Enrollment::query()
            ->where('student_id', $student->id)
            ->whereIn('course_id', $newIds)
            ->with(['course', 'student.studentDetail']) 
            ->get();
    }
}
