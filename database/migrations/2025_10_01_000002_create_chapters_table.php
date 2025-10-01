<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('name');
            $table->timestamps();

            $table->foreign('subject_id')
                  ->references('id')
                  ->on('subjects')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
