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
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->string('Title')->nullable(false);
            $table->string('Description')->nullable(true);
            $table->string('Module')->nullable(true);
            $table->longText('Solution')->nullable(true);
            $table->integer('Manhour')->nullable(true);
            $table->longText('Assumption')->nullable(true);
            $table->uuid('UserId')->nullable(); // Id of assigned user to the req.
            $table->integer('ThirdParty')->default(0); // 1 - Assigned to TP, 0 - No
            $table->uuid('CreatedById')->nullable(false);
            $table->uuid('UpdatedById')->nullable(true);
            $table->uuid('UserStoryId')->nullable(false);
            $table->date('StartDate')->nullable(false);
            $table->date('EndDate')->nullable(false);
            $table->date('ActualStartDate')->nullable(true);
            $table->date('ActualEndDate')->nullable(true);
            $table->string('Duration')->nullable(true);
            $table->string('TimeCompleted')->nullable(true);
            $table->string('Status');
            $table->timestamps();

            $table->foreign('CreatedById')->references('Id')->on('users');
            $table->foreign('UpdatedById')->references('Id')->on('users');
            $table->foreign('UserStoryId')->references('Id')->on('user_story');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
