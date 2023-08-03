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
        Schema::table('application_sections', function (Blueprint $table) {
            $table->string('colour_left')->nullable()->after('name');
            $table->string('colour_right')->nullable()->after('colour_left');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_sections', function (Blueprint $table) {
            $table->dropColumn('colour_left');
            $table->dropColumn('colour_right');
        });
    }
};
