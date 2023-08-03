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
        // Step 2: Drop the 'application_category_id' column
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('application_category_id');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('application_category_id')->constrained('application_categories')->after('user_id');
        });
    }
};
