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
        Schema::create('server_roles', function (Blueprint $table) {
            $table->foreignUuid('server_id')->constrained('servers', 'id')->cascadeOnDelete();
            $table->string('id');
            $table->string('guild');
            $table->string('name');
            $table->integer('priority');
            $table->integer('can_warn')->default(0);
            $table->integer('can_kick')->default(0);
            $table->integer('can_ban')->default(0);
            $table->integer('can_note')->default(0);
            $table->integer('can_commend')->default(0);
            $table->integer('can_view_staff')->default(0);
            $table->integer('can_manage_record')->default(0);
            $table->integer('can_manage_permissions')->default(0);
            $table->integer('can_view_timeclock')->default(0);
            $table->integer('locked')->default(0);
            $table->integer('timeclock_requirements')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_roles');
    }
};
