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
        Schema::create('customer_inscope_requirements', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('CustomerId');
            $table->string('Title');
            $table->longText('Description');
            $table->string('Module');
            $table->longText('Solution');
            $table->integer('Manhour')->nullable(true);
            $table->longText('Assumption');
            $table->integer('ThirdParty')->default(0); // 1 - Assigned to TP, 0 - No
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
        Schema::dropIfExists('customer_inscope_requirements');
    }
};
