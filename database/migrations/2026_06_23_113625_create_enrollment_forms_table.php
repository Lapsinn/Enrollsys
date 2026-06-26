<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollment_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('sex', ['male', 'female'])->nullable();
            $table->enum('applicant_type', ['new', 'old', 'transferee'])->nullable();
            $table->string('program')->nullable();
            $table->integer('year_level')->nullable();
            $table->enum('semester', ['1', '2'])->nullable();
            $table->text('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('last_school')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollment_forms');
    }
};
