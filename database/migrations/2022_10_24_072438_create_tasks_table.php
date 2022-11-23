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
            $table->uuid('Admin_Id');
            $table->string('Title')->nullable(false);
            $table->string('Description')->nullable(true);
            $table->uuid('Created_By_Id')->nullable(false);
            $table->uuid('Updated_By_Id')->nullable(true);
            $table->uuid('UserStoryId')->nullable(false);
            $table->date('StartDate')->nullable(false);
            $table->date('EndDate')->nullable(false);
            $table->dateTime('ActualStartDate')->nullable(true);
            $table->dateTime('ActualEndDate')->nullable(true);
            $table->integer('Duration')->nullable(true);
            $table->string('TimeCompleted')->nullable(true);
            $table->uuid('UserId')->nullable(true);
            $table->string('Status');
            $table->timestamps();

            $table->foreign('Created_By_Id')->references('Id')->on('users');
            $table->foreign('Updated_By_Id')->references('Id')->on('users');
            $table->foreign('UserStoryId')->references('Id')->on('user_story')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
