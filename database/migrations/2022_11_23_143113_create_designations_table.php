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
        Schema::create('designations', function (Blueprint $table) {
            $table->uuid('Id');
            $table->uuid('DepartmentId');
            $table->string('Name');
            $table->string('Initial')->nullable();
            $table->decimal('BeginnerRate', 15, 2)->default(0.00);
            $table->decimal('IntermediateRate', 15, 2)->default(0.00);
            $table->decimal('SeniorRate', 15, 2)->default(0.00);
            $table->decimal('ExpertRate', 15, 2)->default(0.00);
            $table->decimal('DefaultRate', 15, 2)->default(0.00);
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
        Schema::dropIfExists('designations');
    }
};
