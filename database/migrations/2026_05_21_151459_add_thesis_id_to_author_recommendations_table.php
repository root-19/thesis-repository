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
        Schema::table('author_recommendations', function (Blueprint $table) {
            $table->foreignId('thesis_id')->nullable()->after('recommender_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('author_recommendations', function (Blueprint $table) {
            $table->dropForeign(['thesis_id']);
            $table->dropColumn('thesis_id');
        });
    }
};
