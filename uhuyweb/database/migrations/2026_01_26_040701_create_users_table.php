<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('fullName', 100)->nullable();
            $table->text('bio')->nullable();
            $table->string('profileImage')->nullable()->default('default-avatar.jpg');
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->timestamps(); // Menangani createdAt & updatedAt
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};