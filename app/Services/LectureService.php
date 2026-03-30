<?php

namespace App\Services;

use App\Data\LectureData;
use App\Models\Lecture;
use Illuminate\Support\Facades\DB;

class LectureService
{
    public function store(LectureData $data): Lecture
    {
        return DB::transaction(function () use ($data) {
            $lecture = Lecture::create($data->onlyModelAttributes());

            return $lecture;
        });
    }

    public function update(Lecture $lecture, LectureData $data): Lecture
    {
        return DB::transaction(function () use ($lecture, $data) {
            tap($lecture)->update($data->onlyModelAttributes());

            return $lecture;
        });
    }

    
}
