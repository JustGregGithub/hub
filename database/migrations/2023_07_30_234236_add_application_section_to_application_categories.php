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
        Schema::table('application_categories', function (Blueprint $table) {
            $table->foreignId('application_section_id')->nullable()->after('is_open')->constrained('application_sections');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_categories', function (Blueprint $table) {
            $table->dropForeign(['application_section_id']);
            $table->dropColumn('application_section_id');
        });
    }
};
