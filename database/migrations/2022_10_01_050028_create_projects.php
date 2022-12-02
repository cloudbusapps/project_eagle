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
            $table->string('Name')->nullable(false);
            $table->string('Description')->nullable(false);
            $table->date('KickoffDate')->nullable(false);
            $table->date('ClosedDate')->nullable(false);
            $table->json('UsersId')->nullable(true);

            // will change it to ProjectManagerID if needed
            $table->uuid('ProjectManagerId')->nullable(true);
            $table->uuid('CreatedById')->nullable(false);
            $table->uuid('UpdatedById')->nullable(true);
            $table->timestamps();

            $table->foreign('CreatedById')->references('Id')->on('users');
            $table->foreign('UpdatedById')->references('Id')->on('users');
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
