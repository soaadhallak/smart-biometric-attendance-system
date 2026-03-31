<?php

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
        Schema::dropIfExists('devices');

        Schema::table('student_details', function (Blueprint $table) {
            if (!Schema::hasColumn('student_details', 'device_id')) {
                $table->string('device_id')->nullable()->after('year_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_details', function (Blueprint $table) {
            if (Schema::hasColumn('student_details', 'device_id')) {
                $table->dropColumn('device_id');
            }
        });

        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->string('device_id');
            $table->timestamps();
            $table->unique(['student_id', 'device_id']);
        });
    }
};