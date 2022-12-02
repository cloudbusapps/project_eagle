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
        Schema::create('resource_costs', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('UserId');
            $table->string('Level');
            $table->decimal('BasicSalary', 15, 2);
            $table->decimal('DailyRate', 15, 2);
            $table->decimal('HourlyRate', 15, 2);
            $table->uuid('CreatedById');
            $table->uuid('UpdatedById');
            $table->timestamps();

            $table->foreign('UserId')->references('Id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resource_costs');
    }
};
