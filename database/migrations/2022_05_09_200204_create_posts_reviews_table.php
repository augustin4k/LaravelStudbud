<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('for_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('by_user')->references('id')->on('users')->onDelete('cascade');
            $table->text('text');
            $table->string('type');
            $table->integer('note')->nullable();
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
        Schema::dropIfExists('posts_reviews');
    }
};
