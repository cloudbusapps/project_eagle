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
        Schema::create('user_story', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('Admin_Id');
            $table->string('Title')->nullable(false);
            $table->string('Description')->nullable(true);
            $table->uuid('CreatedById')->nullable(false);
            $table->uuid('UpdatedById')->nullable(true);
            
            $table->uuid('ProjectId')->nullable(false);
            $table->date('StartDate')->nullable(true);
            $table->date('EndDate')->nullable(true);
            $table->date('ActualStartDate')->nullable(true);
            $table->date('ActualEndDate')->nullable(true);
            $table->uuid('UserId')->nullable(true);
            $table->string('Status')->nullable(true);
            $table->string('PercentComplete')->nullable(true);
            $table->timestamps();

            $table->foreign('CreatedById')->references('Id')->on('users');
            $table->foreign('UpdatedById')->references('Id')->on('users');
            $table->foreign('ProjectId')->references('Id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_story');
    }
};
