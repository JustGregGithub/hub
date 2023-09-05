<?php

use App\Models\Staff\PlayerActivity;
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
        Schema::create('player_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('server_id')->constrained('servers');
            $table->foreignId('player_id')->constrained('players');
            $table->integer('type')->default(PlayerActivity::TYPES['Join']);
            $table->longText('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_activities');
    }
};
