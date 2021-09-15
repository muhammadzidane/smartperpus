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
            $enum_payment_method = array(
                'Transfer Bank BRI',
                'Transfer Bank BNI',
                'Transfer Bank BCA'
            );

            $enum_payment_status = array(
                'failed',
                'waiting_for_confirmation',
                'order_in_process',
                'being_shipped',
                'arrived'
            );

            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained();
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
            $table->enum('payment_method', $enum_payment_method);
            $table->enum('payment_status', $enum_payment_status);
            $table->string('upload_payment_image')->nullable();
            $table->boolean('confirmed_payment')->default(false);
            $table->string('failed_message')->nullable();
            $table->string('resi_number')->nullable();
            $table->dateTime('payment_deadline')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->dateTime('shipped_date')->nullable();
            $table->datetime('completed_date')->nullable();
            $table->datetime('failed_date')->nullable();
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
