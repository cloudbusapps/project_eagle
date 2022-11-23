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
        Schema::create('user_certifications', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('UserId');
            $table->string('Code');
            $table->text('Description');
            $table->date('DateTaken')->nullable();
            $table->string('Status');
            $table->uuid('Created_By_Id');
            $table->uuid('Updated_By_Id');
            $table->timestamps();

            $table->foreign('UserId')->references('Id')->on('users');
            $table->foreign('Created_By_Id')->references('Id')->on('users');
            $table->foreign('Updated_By_Id')->references('Id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_certifications');
    }
};
