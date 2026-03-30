<?php

use App\Models\User;
use App\Models\Year;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_details', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->primary()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Year::class)->constrained()->cascadeOnDelete();
            $table->string('university_number')->unique();
            $table->longText('fingerprint_template');
            $table->string('fingerprint_identifier')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_details');
    }
};
