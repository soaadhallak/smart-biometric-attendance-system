<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\RegisterTeacherAction;
use App\Data\UserData;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterTeacherRequest;
use App\Http\Requests\TeacherUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TeacherController extends Controller
{
    public function __construct(
        protected UserService $userService
    )
    {
    }


    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $teachers = User::role('teacher')->paginate(10);

        return UserResource::collection($teachers)
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message()
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterTeacherRequest $request): JsonResponse
    {
        $teacher = $this->userService->store(UserData::from($request->validated()));

        return UserResource::make($teacher->load('roles'))
         ->additional([
                'message' => ResponseMessages::CREATED->message(),
            ])->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $teacher): UserResource
    {
        return UserResource::make($teacher->load('roles'))
        ->additional([
                'message' => ResponseMessages::RETRIEVED->message(),
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeacherUpdateRequest $request, User $teacher)
    {
        $teacher = $this->userService->update($teacher, UserData::from($request->validated()));
        return UserResource::make($teacher->load('roles'))
            ->additional([
                'message' => ResponseMessages::UPDATED->message()
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $teacher): UserResource
    {
        $teacher->delete();

        return UserResource::make($teacher)
            ->additional([
                'message' => ResponseMessages::DELETED->message()
            ]);
    }
}
