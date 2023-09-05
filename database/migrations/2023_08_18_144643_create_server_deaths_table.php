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
        Schema::create('server_deaths', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('server_id')->constrained();
            $table->foreignId('player_id')->nullable()->constrained();
            $table->foreignId('killer_id')->constrained('players');
            $table->string('cause')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_deaths');
    }
};
