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
        Schema::create('user_experiences', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('UserId');
            $table->string('JobTitle');
            $table->string('Company');
            $table->text('Description');
            $table->date('StartDate');
            $table->date('EndDate');
            $table->uuid('CreatedById');
            $table->uuid('UpdatedById');
            $table->timestamps();

            $table->foreign('UserId')->references('Id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_experiences');
    }
};
