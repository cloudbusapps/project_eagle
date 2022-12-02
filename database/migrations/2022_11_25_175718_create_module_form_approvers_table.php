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
        Schema::create('module_form_approvers', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->bigInteger('ModuleId');
            $table->uuid('TableId');
            $table->integer('Level');
            $table->uuid('ApproverId');
            $table->datetime('Date')->nullable();
            $table->integer('Status')->nullable();
            $table->string('Remarks')->nullable();
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
        Schema::dropIfExists('module_form_approvers');
    }
};
