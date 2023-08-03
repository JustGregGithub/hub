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
        Schema::table('applications', function (Blueprint $table) {
            //foreign key to users table with id
            $table->foreignId('worker_id')->nullable()->after('status')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['worker_id']);
            // Drop the column
            $table->dropColumn('worker_id');
        });
    }

};
