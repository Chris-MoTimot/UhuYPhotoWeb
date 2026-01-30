<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            // Foreign Key ke Users
            $table->foreignId('userId')
                  ->constrained('users')
                  ->onUpdate('cascade'); 
            
            // Foreign Key ke Categories (Nullable)
            $table->foreignId('categoryId')
                  ->nullable()
                  ->constrained('categories')
                  ->onUpdate('cascade')
                  ->onDelete('set null');

            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('imageUrl');
            $table->string('tags')->nullable();
            $table->integer('likes')->default(0)->nullable();
            $table->integer('views')->default(0)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};