<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->char('isbn', 13)->unique();
            $table->string('name')->unique();
            $table->integer('price');
            $table->string('image');
            $table->foreignId('author_id')->constrained()->onDelete('cascade')->unique();
            $table->float('rating', 2, 1);
            $table->integer('discount')->nullable();
            $table->boolean('ebook');
            $table->string('pages');
            $table->date('release_date');
            $table->string('publisher');
            $table->string('subtitle');
            $table->integer('weight');
            $table->float('width');
            $table->float('height');
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
        Schema::dropIfExists('books');
    }
}
