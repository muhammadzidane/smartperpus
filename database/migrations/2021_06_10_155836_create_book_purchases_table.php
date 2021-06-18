<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->enum('book_version', array('ebook', 'hard_cover'));
            $table->integer('amount');
            $table->string('courier_name');
            $table->string('courier_service');
            $table->integer('shipping_cost');
            $table->string('note');
            $table->integer('insurance');
            $table->integer('unique_code');
            $table->integer('total_payment');
            $table->enum('payment_method', array('Transfer Bank BRI', 'Transfer Bank BNI', 'Transfer Bank BCA'));
            $table->dateTime('payment_deadline');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_purchases');
    }
}
