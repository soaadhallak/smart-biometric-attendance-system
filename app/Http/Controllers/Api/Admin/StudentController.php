<?php

namespace App\Http\Controllers\Api\Admin;

use App\Data\StudentData;
use App\Data\UserData;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\StudentPasswordUpdateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $students = User::role('student')->with(['studentDetail.year', 'roles'])->paginate(10);

        return UserResource::collection($students)
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message()
            ]);
    }

    
    /**
     * Display the specified resource.
     */
    public function show(User $user): UserResource
    {
        return UserResource::make($user->load(['studentDetail.year', 'roles']))
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message()
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentPasswordUpdateRequest $request, User $user, UserService $userService): UserResource
    {
        $student = $userService->update($user, UserData::from($request->validated()));

        return  UserResource::make($student->load(['studentDetail.year', 'roles']))
        ->additional([
            'message' => ResponseMessages::UPDATED->message()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): UserResource
    {
        $user->delete();

        return  UserResource::make($user->load(['studentDetail.year', 'roles']))
        ->additional([
            'message' => ResponseMessages::DELETED->message()
        ]);
    }
}
