<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id(); // Primary key, auto-incrementing
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Foreign key to users table
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade'); // Foreign key to users table
            $table->string('reminder_name');
            $table->string('medication_types'); // String to handle medication type
            $table->string('pills_number');
            $table->time('time');
            $table->string('frequency');
            $table->string('instructions')->nullable();
            $table->string('side_effects')->nullable();
            $table->timestamps(); // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reminders');
    }
}
