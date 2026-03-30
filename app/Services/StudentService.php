<?php

namespace App\Services;

use App\Data\StudentData;
use App\Models\Student;
use App\Models\StudentDetail;
use Illuminate\Support\Facades\DB;

class StudentService
{
    public function update(StudentDetail $student,StudentData $studentData): StudentDetail
    {
        return DB::transaction(function() use ($student,$studentData){

            tap($student)->update($studentData->onlyModelAttributes());

            return $student;
        });
    }
}
