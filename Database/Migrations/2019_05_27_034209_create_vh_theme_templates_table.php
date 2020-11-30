<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVhThemeTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vh_theme_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vh_theme_id')->nullable();
            $table->string('type')->nullable();
            $table->string('name',150)->nullable();
            $table->string('slug',150)->nullable();
            $table->string('file_path')->nullable();
            $table->string('excerpt')->nullable();
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
        Schema::dropIfExists('vh_theme_templates');
    }
}