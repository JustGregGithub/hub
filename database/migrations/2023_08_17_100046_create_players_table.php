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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('server_id')->constrained('servers')->cascadeOnDelete();
            $table->string('name');
            $table->string('license');
            $table->string('steam')->nullable();
            $table->string('discord')->nullable();
            $table->string('ip');
            $table->json('old_licenses')->default('{}');
            $table->integer('trust_score')->default(50);
            $table->bigInteger('playtime')->default(0);
            $table->integer('online')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
