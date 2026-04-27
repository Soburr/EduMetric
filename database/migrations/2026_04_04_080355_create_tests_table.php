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
        Schema::create('tests', function (Blueprint $table) {
           $table->id();
           $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
           $table->foreignId('class_id')->constrained('school_classes')->cascadeOnDelete();
           $table->string('subject');
           $table->string('title');
           $table->unsignedInteger('duration_minutes')->default(30);
           $table->boolean('is_active')->default(false);
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
