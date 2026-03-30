<?php
namespace App\Actions;

use App\Data\UserData;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class RegisterTeacherAction
{
    public function execute(UserData $userData): array
    {
        return DB::transaction(static function () use ($userData)
        {
            $user = User::create($userData->onlyModelAttributes());
            $user->assignRole('teacher');

            $token = $user->createToken('user-token')->plainTextToken;

            return[
                'user' => $user,
                'token' => $token
            ];

        });
    }

}
