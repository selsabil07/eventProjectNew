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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('eventTitle');
            $table->string('country');
            $table->string('tags');
            $table->string('sector');
            $table->string('photo')->nullable();
            $table->string('summary');
            $table->string('description'); 
            $table->date('startingDate');
            $table->date('endingDate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
