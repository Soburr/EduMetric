<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->string('subject');
            $table->enum('term', ['first', 'second', 'third']);
            $table->string('session'); 
            $table->decimal('cbt_score', 5, 2)->default(0);   
            $table->decimal('ca_score',  5, 2)->nullable();  
            $table->decimal('exam_score',5, 2)->nullable();   
            $table->decimal('total',     5, 2)->default(0);   
            $table->string('grade')->nullable();               
            $table->text('remark')->nullable();        
            $table->timestamps();

            // One record per student per subject per term per session
            $table->unique(['student_id', 'subject', 'term', 'session'], 'unique_score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_scores');
    }
};