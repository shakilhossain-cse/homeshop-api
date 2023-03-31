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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('companyName')->nullable();
            $table->string('country');
            $table->string('streetAddress');
            $table->string('townCity');
            $table->string('zipCode');
            $table->string('phoneNumber');
            $table->string('email');
            $table->unsignedBigInteger('user_id')->unique();
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
        Schema::dropIfExists('users');
    }
};
