<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('rating')->default(1500);
            $table->integer('rank')->default(0);
            $table->integer('max_rating')->default(1500);
            $table->integer('total_solved')->default(0);
            $table->json('rating_history')->nullable();
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->boolean('is_rated')->default(false)->after('exam_type');
            $table->integer('difficulty_level')->default(0)->after('is_rated');
            $table->json('performance_stats')->nullable()->after('difficulty_level');
        });

        // Create table for rating changes
        Schema::create('rating_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->integer('old_rating');
            $table->integer('new_rating');
            $table->integer('rank_in_contest');
            $table->timestamps();
        });

        // Create table for exam statistics
        Schema::create('exam_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->integer('total_participants')->default(0);
            $table->float('average_score')->default(0);
            $table->float('highest_score')->default(0);
            $table->float('lowest_score')->default(0);
            $table->json('score_distribution')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rating', 'rank', 'max_rating', 'total_solved', 'rating_history']);
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['is_rated', 'difficulty_level', 'performance_stats']);
        });

        Schema::dropIfExists('rating_changes');
        Schema::dropIfExists('exam_statistics');
    }
};