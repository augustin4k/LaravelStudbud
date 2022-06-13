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
        Schema::create('compartment_file', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->references('id')->on('files')->onDelete('cascade');
            $table->foreignId('compartment_id')->references('id')->on('compartments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compartments_files');
    }
};
