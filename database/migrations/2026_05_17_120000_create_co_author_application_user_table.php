<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('co_author_application_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('co_author_application_id')->constrained('co_author_applications')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['co_author_application_id', 'user_id'], 'co_author_app_user_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('co_author_application_user');
    }
};
