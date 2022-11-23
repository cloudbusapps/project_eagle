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
        Schema::create('user_skills', function (Blueprint $table) {
            $table->uuid('Id');
            $table->uuid('UserId');
            $table->string('Title');
            $table->uuid('Created_By_Id');
            $table->uuid('Updated_By_Id');
            $table->timestamps();

            $table->foreign('UserId')->references('Id')->on('users')->onDelete('cascade');
            $table->foreign('Created_By_Id')->references('Id')->on('users')->onDelete('cascade');
            $table->foreign('Updated_By_Id')->references('Id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_skills');
    }
};
