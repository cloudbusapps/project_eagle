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
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->string('Name');
            $table->string('Description')->nullable();
            $table->string('CustomerId')->nullable();
            $table->date('KickoffDate')->nullable();
            $table->date('ClosedDate')->nullable();
            $table->json('UsersId')->nullable();

            /**
             * ----- IsComplex -----
             * 0 or null - Not Applicable
             * 1 - Complex
             * 2 - Intermediate
             * 3 - Easy
             */
            $table->integer('IsComplex')->nullable();

            // will change it to ProjectManagerID if needed
            $table->uuid('ProjectManagerId')->nullable();
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
        Schema::dropIfExists('projects');
    }
};
