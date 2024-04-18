<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLivraisonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('livraisons', function (Blueprint $table) {
            $table->id();
            $table->integer('id_devis');
            $table->integer('id_equipe');
            $table->date('date_livraison');
            $table->string('img_recup')->nullable();
            $table->string('img_livr')->nullable();
            $table->integer('etat')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_devis')->references('id')->on('devis');
            $table->foreign('id_equipe')->references('id')->on('equipes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('livraisons');
    }
}
