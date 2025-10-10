<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->enum('exam_type', ['board', 'university', 'custom'])->after('name');
            $table->string('institution_name')->nullable()->after('exam_type');
            $table->integer('year')->nullable()->after('institution_name');
            $table->json('custom_criteria')->nullable()->after('year');
            $table->integer('duration')->nullable()->after('end_time'); // Duration in minutes
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['exam_type', 'institution_name', 'year', 'custom_criteria', 'duration']);
        });
    }
};