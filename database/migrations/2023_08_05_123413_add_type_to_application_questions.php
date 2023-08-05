<?php

use App\Models\ApplicationQuestion;
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
        Schema::table('application_questions', function (Blueprint $table) {
            $table->integer('type')->default(ApplicationQuestion::OPTION_TYPES['Input'])->after('question');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_questions', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
