<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table patients
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone');
            $table->date('date_naissance');
            $table->text('adresse');
            $table->text('antecedents_medicaux')->nullable();
            $table->timestamps();
            $table->index('created_at');
        });

    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
};

