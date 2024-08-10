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
            $table->foreignId('teacher_id')->constrained('users')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('student_id')->constrained('students')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('instrument_id')->constrained('instruments')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('room_id')->constrained('rooms')
                ->onDelete('cascade')->onUpdate('cascade')->nullable();
            $table->json('planning');
            $table->json('instrument_plan');
            $table->integer('frequency')->default(1);
            $table->integer('confirmed')->default(1);
            $table->string('color')->default('#0b6ab9');
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
