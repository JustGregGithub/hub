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
            $table->string('guild')->after('information')->default(env('DISCORD_GUILD_ID'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_categories', function (Blueprint $table) {
            $table->dropColumn('guild');
        });
    }
};
