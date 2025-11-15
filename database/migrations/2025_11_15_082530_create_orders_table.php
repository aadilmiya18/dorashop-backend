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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_id')->unique(); // esewa pid
            $table->string('status')->default('pending'); //pending , paid , failed

            //billing information
            $table->string('name');
            $table->string('email');
            $table->string('mobile');
            $table->text('address');


            //payment details
            $table->decimal('subtotal',10,2);
            $table->decimal('shipping',10,2)->default(100);
            $table->decimal('total',10,2);

            //esewa
            $table->string('ref_id')->nullable(); // esewa reference id

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
