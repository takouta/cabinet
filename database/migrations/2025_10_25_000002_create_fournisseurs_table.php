<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
       // Table fournisseurs
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('email')->unique();
            $table->string('telephone');
            $table->text('adresse');
            $table->string('specialite');
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('fournisseurs');
    }
};

