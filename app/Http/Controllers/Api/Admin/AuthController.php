<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\LoginTeacherAction;
use App\Actions\RegisterTeacherAction;
use App\Data\UserData;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginTeacherRequest;
use App\Http\Requests\RegisterTeacherRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterTeacherRequest $request, RegisterTeacherAction $registerTeacherAction): JsonResponse
    {
        $result = $registerTeacherAction->execute(UserData::from($request->validated()));
        $user = $result['user'];
        $token = $result['token'];

        return UserResource::make($user->load(['roles']))
            ->additional([
                'message' => ResponseMessages::CREATED->message(),
                'token' => $token
            ])->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function login(LoginTeacherRequest $request, LoginTeacherAction $loginUserAction): UserResource
    {
        $result = $loginUserAction->execute(UserData::from($request->validated()));
        $user=$result['user'];
        $token=$result['token'];

        return UserResource::make($user->load(['roles']))
            ->additional([
                'message'=>ResponseMessages::RETRIEVED->message(),
                'token'=>$token
            ]);
    }

    public function logout(Request $request): UserResource
    {
        $user=$request->user();
        $user->currentAccessToken()->delete();

        return UserResource::make($user->load(['roles']))
            ->additional([
                'message'=>ResponseMessages::DELETED->message(),
            ]);
    }
}
