<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subject_scores', function (Blueprint $table) {
            $table->decimal('test1_score', 5, 2)->nullable()->after('cbt_score');
            $table->decimal('test2_score', 5, 2)->nullable()->after('test1_score');
        });
    }

    public function down(): void
    {
        Schema::table('subject_scores', function (Blueprint $table) {
            $table->dropColumn(['test1_score', 'test2_score']);
        });
    }
};