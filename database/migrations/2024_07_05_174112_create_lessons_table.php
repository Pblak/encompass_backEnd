<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users');
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('instrument_id')->constrained('instruments');
            $table->foreignId('room_id')->constrained('rooms')->nullable();
            $table->json('planning');
            $table->integer('frequency')->default(1);
            $table->integer('duration')->default(30);
            $table->integer('price')->default(0);
            $table->integer('active')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
