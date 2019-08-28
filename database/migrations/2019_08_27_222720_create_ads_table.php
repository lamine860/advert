<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('title')->unique();
            $table->text('texte');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('region_id');
            $table->integer('user_id')->default(0);
            $table->string('departement');
            $table->string('commune');
            $table->string('commune_name');
            $table->string('commune_postal');
            $table->string('pseudo');
            $table->string('email');
            $table->date('limit');
            $table->boolean('active');

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('region_id')->references('id')->on('regions');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads');
    }
}
