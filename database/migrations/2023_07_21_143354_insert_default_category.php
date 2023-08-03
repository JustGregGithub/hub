<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::insert('INSERT INTO `ticket_categories` (`name`, `deletable`, `created_at`, `updated_at`) values (?, ?, ?, ?)', [
            'No category assigned',
            false,
            Carbon::now(),
            Carbon::now(),
        ]);
    }

    /**,
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::delete("DELETE FROM `ticket_categories` WHERE `name`=? AND `deletable`=?", [
            'No category assigned',
            false
        ]);
    }
};
