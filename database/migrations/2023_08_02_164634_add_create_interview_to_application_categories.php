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
            $table->boolean('create_interview')->default(false)->after('is_open');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_categories', function (Blueprint $table) {
            $table->dropColumn('create_interview');
        });
    }
};
