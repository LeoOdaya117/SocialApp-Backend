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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->string('image')->nullable(); // Optional image
            $table->unsignedInteger('likes_count')->default(0); // Cached count
            $table->unsignedInteger('comments_count')->default(0); // Cached count
            $table->unsignedInteger('shares_count')->default(0); // Cached count
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
