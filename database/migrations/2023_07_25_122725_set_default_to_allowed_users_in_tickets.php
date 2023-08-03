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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('allowed_users', 500)->default('[]')->change();
        });

        DB::statement("UPDATE tickets SET allowed_users = '[]' WHERE allowed_users IS NULL");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('allowed_users')->default(null)->change();
        });
    }
};
