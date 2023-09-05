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
        Schema::create('player_records', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('server_id')->constrained();
            $table->foreignId('player_id')->constrained();
            $table->foreignId('staff_id')->constrained('users', 'id');
            $table->integer('type');
            $table->string('message');
            $table->timestamp('expiration_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_records');
    }

};
