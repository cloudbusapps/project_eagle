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
        Schema::create('customer_project_phases', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('CustomerId');
            $table->uuid('ProjectPhaseId');
            $table->string('Title');
            $table->decimal('Percentage', 10, 2)->default(0.00);
            $table->integer('Checked')->default(0);
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
        Schema::dropIfExists('customer_project_phases');
    }
};
