<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('invoice', 10);
            $table->enum('book_version', array('ebook', 'hard_cover'));
            $table->integer('amount');
            $table->string('courier_name');
            $table->string('courier_service');
            $table->integer('shipping_cost');
            $table->string('note')->nullable();
            $table->integer('insurance')->nullable();
            $table->smallInteger('unique_code');
            $table->integer('total_payment');
            $table->enum('payment_method', array('Transfer Bank BRI', 'Transfer Bank BNI', 'Transfer Bank BCA'));
            $table->enum('payment_status', array('failed', 'waiting_for_confirmation', 'order_in_process', 'being_shipped', 'arrived'));
            $table->datetime('completed_date')->nullable();
            $table->dateTime('payment_deadline');
            $table->string('upload_payment_image')->nullable()->default(null);
            $table->boolean('confirmed_payment')->default(false);
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
        Schema::dropIfExists('book_user');
    }
}
