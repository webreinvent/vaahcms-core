<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVhLangStringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('vh_lang_strings', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('vh_lang_language_id')->nullable()->index();
            $table->integer('vh_lang_category_id')->nullable()->index();

            $table->string('name',150)->nullable();
            $table->string('slug',150)->nullable()->index();
            $table->mediumText('content')->nullable();


            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();


        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::dropIfExists('vh_lang_strings');
    }
}
