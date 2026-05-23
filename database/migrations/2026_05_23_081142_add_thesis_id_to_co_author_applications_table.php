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
        Schema::table('co_author_applications', function (Blueprint $table) {
            $table->foreignId('thesis_id')->nullable()->constrained('theses')->onDelete('cascade')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('co_author_applications', function (Blueprint $table) {
            $table->dropForeign(['thesis_id']);
            $table->dropColumn('thesis_id');
        });
    }
};
