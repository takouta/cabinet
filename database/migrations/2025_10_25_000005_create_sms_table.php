<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
         // Table sms
        Schema::create('sms', function (Blueprint $table) {
            $table->id();
            $table->string('numero_destinataire');
            $table->text('message');
            $table->enum('type', ['rappel_rdv', 'alerte_stock', 'promotion', 'autre']);
            $table->enum('statut', ['envoyé', 'échoué', 'en_attente'])->default('en_attente');
            $table->timestamp('envoye_a')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sms');
    }
};

