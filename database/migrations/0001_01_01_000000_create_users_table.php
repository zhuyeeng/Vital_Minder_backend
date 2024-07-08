<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key, auto-incrementing ID
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('phone_number')->nullable();
            $table->string('user_role');
            $table->string('identity_card_number')->unique();
            $table->string('status')->default('active');
            $table->timestamps(); // Adds created_at and updated_at columns
        });

        // Insert default admin user
        DB::table('users')->insert([
            'username' => 'admin',
            'email' => 'admin@vitalminder.com',
            'password' => Hash::make('Admin1234@'), // Make sure to hash the password
            'date_of_birth' => '0001-01-01',
            'gender' => 'male',
            'phone_number' => '1234567890',
            'user_role' => 'admin',
            'identity_card_number' => '12345678901',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
