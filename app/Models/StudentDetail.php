<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentDetail extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'university_number',
        'fingerprint_template',
        'year_id',
        'fingerprint_identifier'
    ];

    protected function casts(): array
    {
        return [
            'fingerprint_template' => 'hashed',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function year(): BelongsTo
    {
        return $this->belongsTo(Year::class);
    }
}
