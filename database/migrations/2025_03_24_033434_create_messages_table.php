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
        Schema::create('messages', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('from_id'); // Sender ID
            $table->unsignedBigInteger('to_id'); // Receiver ID
            $table->string('from_id_table'); // Sender table name (users, teachers, etc.)
            $table->string('to_id_table'); // Receiver table name (users, teachers, etc.)
            $table->text('content'); // Message content
            $table->string('type')->default('text');
            $table->timestamp('read_at')->nullable(); // Read timestamp (null if unread)
            $table->timestamps(); // Created_at & updated_at

            // Indexes for faster lookup
            $table->index(['from_id', 'from_id_table']);
            $table->index(['to_id', 'to_id_table']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
