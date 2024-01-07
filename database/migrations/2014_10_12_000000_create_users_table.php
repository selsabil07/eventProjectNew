<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birthday')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('organization')->nullable(); 
            $table->string('password');
            $table->boolean('approved')->default(0)->nullable();
            $table->boolean('status')->default(1)->nullable();//0 = disactivated 1= activated
            $table->string('profile_photo')->default('profile_photo.jpg')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
    */

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
