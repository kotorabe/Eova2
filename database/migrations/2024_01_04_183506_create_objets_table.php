<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objets', function (Blueprint $table) {
            $table->id();
            $table->integer('id_devis');
            $table->integer('id_taille');
            $table->integer('id_type');
            $table->string('nom');
            $table->integer('quantite');
            $table->integer('kilo');
            $table->timestamps();
            $table->foreign('id_devis')->references('id')->on('devis');
            $table->foreign('id_taille')->references('id')->on('tailles');
            $table->foreign('id_type')->references('id')->on('type_objets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('objets');
    }
}
