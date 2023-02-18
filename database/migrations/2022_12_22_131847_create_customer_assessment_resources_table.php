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
        Schema::create('customer_assessment_resources', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('CustomerId');
            $table->uuid('DesignationId');
            $table->string('Initial');
            $table->string('Level');
            $table->decimal('Rate', 15, 2);
            $table->decimal('Cost', 15, 2);
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
        Schema::dropIfExists('customer_assessment_resources');
    }
};
