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
        Schema::create('medication_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('paramedic_staff_id')->nullable()->constrained('paramedic_staff')->onDelete('cascade');
            $table->string('report_title');
            $table->date('report_created_date');
            $table->text('physical_examination_note');
            $table->text('diagnostic_tests_results')->nullable();
            $table->text('treatment_plan_instruction');
            $table->text('doctor_note')->nullable();
            $table->string('patient_name');
            $table->enum('report_status', ['ended', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_reports');
    }
};
