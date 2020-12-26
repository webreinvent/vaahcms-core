<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVhThemeLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vh_theme_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vh_theme_id')->nullable()->index();
            $table->string('type')->nullable()->index();
            $table->string('name',150)->nullable();
            $table->string('slug',150)->nullable()->index();
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
        Schema::dropIfExists('vh_theme_locations');
    }
}
