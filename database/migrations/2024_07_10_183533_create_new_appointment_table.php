<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->date('date');
            $table->time('time');
            $table->string('type');
            $table->string('blood_type');
            $table->string('details');
            $table->enum('status', ['pending', 'accepted', 'completed', 'rejected'])->default('pending');
            $table->string('reason')->nullable();
            $table->foreignId('paramedic_id')->nullable()->constrained('paramedic_staff')->onDelete('set null');
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->onDelete('set null');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            //$table->foreignId('medical_summary_id')->nullable()->constrained('medical_summaries')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
