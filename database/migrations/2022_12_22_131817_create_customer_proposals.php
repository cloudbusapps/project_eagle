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
        Schema::create('customer_proposals', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('CustomerId');
            $table->date('DateSubmitted')->nullable();
            $table->date('SignedDateSubmitted')->nullable();
            $table->integer('Status')->nullable();
            $table->integer('Aging')->nullable();
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
        Schema::dropIfExists('customer_proposals');
    }
};
