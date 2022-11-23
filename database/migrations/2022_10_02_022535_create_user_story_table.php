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
            $table->uuid('Created_By_Id')->nullable(false);
            $table->uuid('Updated_By_Id')->nullable(true);
            
            $table->uuid('ProjectId')->nullable(false);
            $table->date('StartDate')->nullable(true);
            $table->date('EndDate')->nullable(true);
            $table->date('ActualStartDate')->nullable(true);
            $table->date('ActualEndDate')->nullable(true);
            $table->uuid('UserId')->nullable(true);
            $table->string('Status')->nullable(true);
            $table->string('PercentComplete')->nullable(true);
            $table->timestamps();

            $table->foreign('Created_By_Id')->references('Id')->on('users');
            $table->foreign('Updated_By_Id')->references('Id')->on('users');
            $table->foreign('ProjectId')->references('Id')->on('projects')->onDelete('cascade');;
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
