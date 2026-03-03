<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('telegraph_bot_id')->constrained()->onDelete('cascade');
            $table->string('chat_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->timestamps();

            $table->unique(['telegraph_bot_id', 'chat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
