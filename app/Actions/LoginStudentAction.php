<?php
namespace App\Actions;

use App\Data\UserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Data\StudentData;
use Illuminate\Support\Facades\DB;
use App\Models\StudentDetail;

class LoginStudentAction{

    public function execute(UserData $userData, StudentData $studentData): array
    {
        return DB::transaction(function () use ($userData,$studentData) {

            if ($studentData->universityNumber) {
                $user = $this->loginWithUniversityNumber($studentData->universityNumber, $userData->password);
            }
            else {
                $user = $this->loginWithFingerprint($studentData->fingerprintTemplate);
            }

            $token=$user->createToken('user-token')->plainTextToken;

            return [
                'user'  => $user,
                'token' => $token,
            ];
        });

    }

    private function loginWithUniversityNumber(string $universityNumber, string $password)
    {
        $user = User::whereHas('studentDetail', function ($q) use ($universityNumber) {
            $q->where('university_number', $universityNumber);
        })->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'credentials' => ['Invalid credentials'],
            ]);
        }

        return $user;

    }

    private function loginWithFingerprint(string $fingerprintTemplate): User
    {
        $identifier = hash('sha256', $fingerprintTemplate);

        $student = StudentDetail::with('user')
            ->where('fingerprint_identifier', $identifier)
            ->first();

        if (!$student || !Hash::check($fingerprintTemplate, $student->fingerprint_template)) {
            throw ValidationException::withMessages([
                'fingerprint' => ['Fingerprint not recognized'],
            ]);
        }

        return $student->user;
    }               
}
