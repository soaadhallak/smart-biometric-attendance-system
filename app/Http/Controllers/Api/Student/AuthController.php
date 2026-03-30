<?php

namespace App\Http\Controllers\Api\Student;

use App\Actions\LoginStudentAction;
use App\Actions\RegisterStudentAction;
use App\Data\StudentData;
use App\Data\UserData;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginStudentRequest;
use App\Http\Requests\RegisterStudentRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterStudentRequest $request, RegisterStudentAction $registerNewUserAction): JsonResponse
    {
        $result = $registerNewUserAction->execute(UserData::from($request->validated()), StudentData::from($request->validated()));
        $user = $result['user'];
        $token = $result['token'];

        return UserResource::make($user->load(['studentDetail.year','roles']))
            ->additional([
                'message' => ResponseMessages::CREATED->message(),
                'token' => $token
            ])->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function login(LoginStudentRequest $request, LoginStudentAction $loginUserAction): UserResource
    {
        $result = $loginUserAction->execute(UserData::from($request->validated()), StudentData::from($request->validated()));
        $user=$result['user'];
        $token=$result['token'];

        return UserResource::make($user->load(['studentDetail.year','roles']))
            ->additional([
                'message'=>ResponseMessages::RETRIEVED->message(),
                'token'=>$token
            ]);
    }

    public function logout(Request $request): UserResource
    {
        $user=$request->user();
        $user->currentAccessToken()->delete();

        return UserResource::make($user->load(['studentDetail.year','roles']))
            ->additional([
                'message'=>ResponseMessages::DELETED->message(),
            ]);
    }
}
