<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('author_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recommender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recommended_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('recommended_name')->nullable();
            $table->string('recommended_email')->nullable();
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->index(['recommender_id', 'status']);
            $table->index(['recommended_user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('author_recommendations');
    }
};
