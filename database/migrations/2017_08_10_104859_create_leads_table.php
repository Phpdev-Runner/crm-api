<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->integer('application_type_id')->unsigned();
            $table->integer('creator_id')->unsigned();
            $table->integer('assignee_id')->unsigned();
            $table->string('name');
            $table->boolean('responsive');
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('lead_categories');
            $table->foreign('application_type_id')->references('id')->on('application_types');
            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('assignee_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
