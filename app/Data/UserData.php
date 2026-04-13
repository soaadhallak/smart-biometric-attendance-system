<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Mrmarchone\LaravelAutoCrud\Traits\HasModelAttributes;
use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Optional;

class UserData extends Data
{
    use HasModelAttributes;
    protected static string $model = User::class;

    public function __construct(
        #[Max(255)]
        public ?string $name,
        #[Max(255),Unique('users','email'),Email]
        public ?string $email,
        #[Min(8),Max(255),Confirmed]
        public ?string $password,
        #[Date]
        public ?Carbon $emailVerifiedAt,
        #[Max(255)]
        public ?string $rememberToken,
        public UploadedFile|Optional|null $avatar
    ) {}
}
