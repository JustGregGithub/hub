<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE applications CHANGE category_id application_category_id INT');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE applications CHANGE application_category_id category_id INT');
    }


};
