<?php
namespace App\Actions;

use App\Data\StudentData;
use App\Data\UserData;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class RegisterStudentAction
{
    public function execute(UserData $userData,StudentData $studentData): array
    {
        return DB::transaction(static function () use ($userData,$studentData)
        {
            $user = User::create($userData->onlyModelAttributes());
            $user->assignRole('student');

            $token = $user->createToken('user-token')->plainTextToken;

            $user->studentDetail()->create($studentData->onlyModelAttributes());

            return[
                'user' => $user,
                'token' => $token
            ];

        });
    }

}
