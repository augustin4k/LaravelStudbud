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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("email")->unique();
            $table->timestamp("email_verified_at")->nullable();
            $table->longText("password");
            $table->string("name");
            $table->string("surname");
            $table->date("date_birth");
            $table->date("date_finish")->nullable();
            $table->boolean("prezent_activity")->default(false);
            $table->string("country");
            $table->string("city");
            $table->string("user_type")->nullable();
            $table->string("avatar_path")->nullable();
            $table->rememberToken();
            $table->string('role')->default('simple_user');
            $table->timestamp('online_at')->nullable();
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
        Schema::dropIfExists('users');
    }
};
