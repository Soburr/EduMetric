<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_card_metas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->enum('term', ['first', 'second', 'third']);
            $table->string('session');

            // Attendance
            $table->unsignedInteger('times_opened')->nullable();
            $table->unsignedInteger('times_present')->nullable();
            $table->unsignedInteger('times_absent')->nullable();

            // Term dates
            $table->string('term_begins')->nullable();
            $table->string('term_ends')->nullable();
            $table->string('next_term_begins')->nullable();
            $table->string('terminal_duration')->nullable();

            // Personal development
            $table->unsignedTinyInteger('obedience')->nullable();
            $table->unsignedTinyInteger('honesty')->nullable();
            $table->unsignedTinyInteger('self_control')->nullable();
            $table->unsignedTinyInteger('self_reliance')->nullable();
            $table->unsignedTinyInteger('use_of_initiative')->nullable();

            // Sense of responsibility
            $table->unsignedTinyInteger('punctuality')->nullable();
            $table->unsignedTinyInteger('neatness')->nullable();
            $table->unsignedTinyInteger('perseverance')->nullable();
            $table->unsignedTinyInteger('attendance_rating')->nullable();
            $table->unsignedTinyInteger('attentiveness')->nullable();

            // Social development
            $table->unsignedTinyInteger('courtesy')->nullable();
            $table->unsignedTinyInteger('consideration')->nullable();
            $table->unsignedTinyInteger('sociability')->nullable();
            $table->unsignedTinyInteger('consistency')->nullable();
            $table->unsignedTinyInteger('accept_responsibility')->nullable();

            // Psychomotor
            $table->unsignedTinyInteger('reading_writing')->nullable();
            $table->unsignedTinyInteger('verbal_communication')->nullable();
            $table->unsignedTinyInteger('sports_games')->nullable();
            $table->unsignedTinyInteger('inquisitiveness')->nullable();
            $table->unsignedTinyInteger('dexterity')->nullable();

            // Remarks
            $table->text('class_teacher_comment')->nullable();
            $table->text('principal_comment')->nullable();
            $table->string('promoted_repeated')->nullable();
            $table->string('next_term_date')->nullable();

            $table->timestamps();

            $table->unique(['student_id', 'term', 'session'], 'unique_meta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_card_metas');
    }
};