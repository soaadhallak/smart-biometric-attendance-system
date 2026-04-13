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
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\StudentService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected StudentService $studentService
    )
    {
    }

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

    public function getProfile(): UserResource
    {
        $user = Auth::user();
        return UserResource::make($user->load(['studentDetail.year', 'media']))
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message()
            ]);
    }

    public function editProfile(UpdateProfileRequest $request): UserResource
    {
        $user = $this->userService->update(Auth::user(), UserData::from($request->validated()));
        $student = $this->studentService->update($user->studentDetail, studentData::from($request->validated()));

        return UserResource::make($user->load(['studentDetail.year', 'media']))
            ->additional([
                'message' => ResponseMessages::UPDATED->message()
            ]);
    }
}
