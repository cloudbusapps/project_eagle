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
        Schema::create('project_phases_details', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('ProjectPhaseId');
            $table->string('Title');
            $table->integer('Required')->default(0);
            $table->integer('Status')->default(1);
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
        Schema::dropIfExists('project_phases_details');
    }
};
