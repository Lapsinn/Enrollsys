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
        $table->string('first_name');
        $table->string('last_name');
        $table->string('middle_name')->nullable();
        $table->date('birthdate');
        $table->enum('sex', ['male', 'female']);
        $table->enum('applicant_type', ['new', 'old', 'transferee']);
        $table->string('program');
        $table->integer('year_level');
        $table->enum('semester', ['1', '2']);
        $table->text('address');
        $table->string('contact_number');
        $table->string('emergency_contact');
        $table->string('last_school');
        $table->string('status')->default('pending'); // Useful for approval workflow
        $table->timestamps();
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to users
        $table->string('status')->default('pending'); // 'pending', 'approved', 'rejected'
        $table->timestamps();
    });
}
};
