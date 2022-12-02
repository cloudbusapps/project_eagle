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
        Schema::create('module_approvals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ModuleId');
            $table->uuid('DesignationId')->nullable();
            $table->integer('Level');
            $table->uuid('ApproverId')->nullable();
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
        Schema::dropIfExists('module_approvals');
    }
};
