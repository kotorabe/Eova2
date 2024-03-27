<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->integer('id_devis');
            $table->foreign('id_devis')->references('id')->on('devis');
            $table->string('recuperation');
            $table->string('livraison');
            $table->boolean('acces_recup');
            $table->boolean('acces_livr');
            $table->date('date_demenagement');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE addresses ADD CHECK (date_demenagement >= CURRENT_DATE)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
