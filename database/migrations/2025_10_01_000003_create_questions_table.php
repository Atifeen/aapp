<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('chapter_id')->nullable();
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');
            $table->enum('correct_option', ['A','B','C','D']);
            $table->string('source_name')->nullable();
            $table->string('source_type')->nullable();
            $table->integer('year')->nullable();
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
