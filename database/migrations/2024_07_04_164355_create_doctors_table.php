<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->string('doctor_id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key reference to users table
            $table->string('doctor_name');
            $table->string('doctor_phone_number')->nullable();
            $table->string('doctor_email')->unique();
            $table->string('doctor_password');
            $table->enum('doctor_gender', ['male', 'female', 'other']);
            $table->date('doctor_date_of_birth');
            $table->string('specialization');
            $table->string('clinic_address');
            $table->string('qualifications');
            $table->integer('years_of_experience');
            $table->date('schedule')->nullable;
            $table->enum('account_status', ['active', 'inactive', 'suspended'])->default('active'); // Add account_status column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
}
