<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParamedicStaffTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('paramedic_staff', function (Blueprint $table) {
            $table->string('paramedic_staff_id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key reference to users table
            $table->string('paramedic_staff_name');
            $table->string('paramedic_staff_phone_number')->nullable();
            $table->string('paramedic_staff_email')->unique();
            $table->string('paramedic_staff_password');
            $table->enum('paramedic_staff_gender', ['male', 'female', 'other']);
            $table->date('paramedic_staff_date_of_birth');
            $table->string('qualifications');
            $table->integer('field_experience');
            $table->string('assigned_area');
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
        Schema::dropIfExists('paramedic_staff');
    }
}
