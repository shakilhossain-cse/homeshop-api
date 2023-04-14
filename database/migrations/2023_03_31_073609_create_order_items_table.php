<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('product_id');
                $table->decimal('price');
                $table->integer('quantity');
                $table->enum('size', ["m", "l", "xl", "xxl", "2xl"])->nullable();
                $table->string('color')->nullable();
                $table->timestamps();
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->unique(['order_id', 'product_id']);
            });
        } catch (QueryException $e) {
            // Check if the exception is caused by a unique violation error
            if ($e->errorInfo[1] === 23505) {
                // Perform the necessary action, such as dropping the existing sequence
                DB ::statement('DROP SEQUENCE IF EXISTS order_items_id_seq');
                // Retry creating the table
                $this->up();
            } else {
                // Handle other exceptions
                throw $e;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('products');
    }
};
