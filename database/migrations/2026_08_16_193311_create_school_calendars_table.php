<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_calendars', function (Blueprint $table) {
            $table->id();
            $table->enum('term', ['first', 'second', 'third']);
            $table->string('session');
            $table->unsignedInteger('days_opened')->default(0);
            $table->timestamps();

            $table->unique(['term', 'session'], 'unique_calendar');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_calendars');
    }
};