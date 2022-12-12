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
        Schema::create('project_phases_resources', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('ProjectPhaseId');
            $table->uuid('DesignationId');
            $table->decimal('Percentage', 10, 2)->default(0.00);
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
        Schema::dropIfExists('project_phases_resources');
    }
};
