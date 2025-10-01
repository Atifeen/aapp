<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('chapter_id')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
