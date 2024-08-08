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
        Schema::create('trainings', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('UserId');
            $table->text('Status');
            $table->text('Name');
            $table->text('Type');
            $table->date('StartDate')->nullable();
            $table->date('EndDate')->nullable();
            $table->text('Facilitator');
            $table->text('Purpose');
            $table->text('Attachments')->nullable();
            $table->uuid('CreatedById')->nullable();
            $table->uuid('UpdatedById')->nullable();
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
        Schema::dropIfExists('trainings');
    }
};
