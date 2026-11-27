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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('national_number')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('student_number')->nullable()->unique();
            $table->foreignId('section_id')
                ->nullable()
                ->constrained('sections')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropUnique(['student_number']);
            $table->dropColumn([
                'role',
                'first_name',
                'last_name',
                'national_number',
                'address',
                'phone',
                'student_number',
                'section_id',
            ]);
        });
    }
};
