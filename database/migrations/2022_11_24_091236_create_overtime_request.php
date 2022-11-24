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
        Schema::create('overtime_requests', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('UserId');
            $table->string('Agenda');
            $table->date('Date');
            $table->time('TimeIn');
            $table->time('TimeOut');
            $table->string('Reason')->nullable(true);
            $table->integer('Status')->default(0);
            $table->uuid('Created_By_Id');
            $table->uuid('Updated_By_Id')->nullable(true);
            $table->timestamps();

            $table->foreign('UserId')->references('Id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overtime_request');
    }
};
