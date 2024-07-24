<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHealthInformationToPatientsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('blood_pressure')->nullable();
            $table->string('blood_sugar')->nullable();
            $table->decimal('height', 5, 2)->nullable(); // height in cm
            $table->decimal('weight', 5, 2)->nullable(); // weight in kg
            $table->string('medical_history')->nullable(); // detailed medical history
            $table->string('medications')->nullable(); // current medications
            $table->string('emergency_contact')->nullable(); // emergency contact information
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'blood_pressure',
                'blood_sugar',
                'height',
                'weight',
                'medical_history',
                'medications',
                'emergency_contact'
            ]);
        });
    }
}
