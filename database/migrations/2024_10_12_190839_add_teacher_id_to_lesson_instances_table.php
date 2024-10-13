<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lesson_instances', function (Blueprint $table) {
          // add teacher_id column after room_id
            $table->unsignedBigInteger('teacher_id')->after('room_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_instances', function (Blueprint $table) {
            $table->dropColumn('teacher_id'); // add this line
        });
    }
};
