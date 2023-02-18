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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('CategoryId')->nullable();
            $table->bigInteger('ParentId')->nullable();
            $table->string('Title');
            $table->boolean('WithApproval')->default(false);
            $table->string('RouteName')->nullable();
            $table->string('Prefix');
            $table->string('Icon')->default('default.png');
            $table->integer('SortOrder')->default(1);
            $table->string('TableName')->nullable();
            $table->integer('Status')->default(1);
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
        Schema::dropIfExists('modules');
    }
};
