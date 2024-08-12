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
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('instrument_id');
            $table->unsignedBigInteger('room_id');
            $table->json('planning');
            $table->json('instrument_plan');
            $table->integer('frequency')->default(1);
            $table->integer('confirmed')->default(1);
            $table->string('color')->default('#0b6ab9');
            $table->text('notes')->nullable();
            $table->timestamps();

//            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade')->onUpdate('cascade');
//            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade')->onUpdate('cascade');
//            $table->foreign('instrument_id')->references('id')->on('instruments')->onDelete('cascade')->onUpdate('cascade');
//            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade')->onUpdate('cascade');
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
