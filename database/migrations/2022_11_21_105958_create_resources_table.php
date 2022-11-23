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
        Schema::create('resources', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('ProjectId')->nullable(false);
            $table->uuid('UserId')->nullable(true);
            $table->timestamps();

            $table->foreign('ProjectId')->references('Id')->on('projects')->onDelete('cascade');
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
        Schema::dropIfExists('resources');
    }
};
