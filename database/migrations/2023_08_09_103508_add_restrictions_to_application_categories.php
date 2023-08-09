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
            $table->integer('restrict')->default(0)->after('application_section_id');
            $table->string('restrict_guild')->nullable()->after('restrict');
            $table->string('restrict_role')->nullable()->after('restrict_guild');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_categories', function (Blueprint $table) {
            $table->dropColumn('restrict');
            $table->dropColumn('restrict_guild');
            $table->dropColumn('restrict_role');
        });
    }
};
