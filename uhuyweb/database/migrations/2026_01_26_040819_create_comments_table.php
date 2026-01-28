<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            // Foreign Key ke Posts
            $table->foreignId('postId')
                  ->constrained('posts')
                  ->onUpdate('cascade');
            
            // Foreign Key ke Users
            $table->foreignId('userId')
                  ->constrained('users')
                  ->onUpdate('cascade');
                  
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};