<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
               // Table rendez_vous
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('dentiste_id')->constrained('users')->onDelete('cascade');
            $table->datetime('date_heure')->index();
            $table->string('type_consultation');
            $table->enum('statut', ['planifié', 'confirmé', 'annulé', 'terminé'])->default('planifié');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('rendez_vous');
    }
};

