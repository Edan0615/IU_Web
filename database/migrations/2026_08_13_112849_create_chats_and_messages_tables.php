<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_token', 64)->index();
            $table->string('title')->default('Counseling Session');
            $table->string('dominant_function')->nullable();
            $table->boolean('in_loop')->default(false);
            $table->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['user', 'assistant', 'system']);
            $table->text('content');
            $table->json('cognitive_tags')->nullable(); // e.g. ["Ti", "Ni"]
            $table->json('analysis_metadata')->nullable(); // sentiment, detected loop, rotation technique used
            $table->timestamps();
        });

        Schema::create('cognitive_states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained()->onDelete('cascade');
            $table->string('primary_function', 10);
            $table->string('secondary_function', 10)->nullable();
            $table->text('loop_detected')->nullable();
            $table->text('rotation_applied')->nullable();
            $table->float('emotional_clarity_score')->default(0.5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cognitive_states');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chats');
    }
};
