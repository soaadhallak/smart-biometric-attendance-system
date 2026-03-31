<?php

namespace App\Actions;

use App\Data\UserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Data\StudentData;
use Illuminate\Support\Facades\DB;
use App\Models\StudentDetail;

class LoginStudentAction
{

    public function execute(UserData $userData, StudentData $studentData): array
    {
        return DB::transaction(function () use ($userData, $studentData) {

            $user = User::whereHas('studentDetail', function ($q) use ($studentData) {
                $q->where('university_number', $studentData->universityNumber);
            })->first();

            if (!$user || !Hash::check($userData->password, $user->password)) {
                throw ValidationException::withMessages([
                    'credentials' => ['Invalid credentials'],
                ]);
            }

            $token = $user->createToken('user-token')->plainTextToken;

            $studentDetail = $user->studentDetail;

            if (is_null($studentDetail->device_id)) {
                    $studentDetail->update($studentData->onlyModelAttributes());
                } else {
                    if ($studentData->deviceId !== $studentDetail->device_id) {
                        throw ValidationException::withMessages([
                            'device' => ['You can only login from the device you registered with.'],
                        ]);
                    }
                }

            return [
                'user'  => $user,
                'token' => $token,
            ];
        });
    }
}
