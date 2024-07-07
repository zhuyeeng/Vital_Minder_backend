<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateScheduleNullableInParamedicStaffTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('paramedic_staff', function (Blueprint $table) {
            $table->date('schedule')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paramedic_staff', function (Blueprint $table) {
            $table->date('schedule')->nullable(false)->change();
        });
    }
}
