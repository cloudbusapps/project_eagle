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
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('DesignationId');
            $table->bigInteger('ModuleId');
            $table->integer('Read')->default(0);
            $table->integer('Create')->default(0);
            $table->integer('Edit')->default(0);
            $table->integer('Delete')->default(0);
            $table->uuid('CreatedById');
            $table->uuid('UpdatedById');
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
        Schema::dropIfExists('permissions');
    }
};
