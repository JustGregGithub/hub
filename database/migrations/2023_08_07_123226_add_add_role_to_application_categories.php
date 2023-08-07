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
            $table->integer('add_role')->default(0)->after('interview_category');
            $table->string('role_guild')->nullable()->after('add_role');
            $table->string('role')->nullable()->after('guild');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_categories', function (Blueprint $table) {
            $table->dropColumn('add_role');
            $table->dropColumn('role_guild');
            $table->dropColumn('role');
        });
    }
};
