<?php

namespace App\Services;

use App\Data\UserData;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function update(User $user, UserData $userData): User
    {
        return DB::transaction(function () use ($user, $userData) {

            tap($user)->update($userData->onlyModelAttributes());

            return $user;
        });
    }

    public function store(UserData $userData): User
    {
        return DB::transaction(function () use ($userData) {
            $user = User::create($userData->onlyModelAttributes());
            $user->assignRole('teacher');

            return $user;
        });
    }
}
