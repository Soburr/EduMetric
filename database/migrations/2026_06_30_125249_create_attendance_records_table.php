<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['present', 'absent'])->default('present');
            $table->enum('term', ['first', 'second', 'third']);
            $table->string('session');
            $table->foreignId('marked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'date'], 'unique_attendance_day');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};