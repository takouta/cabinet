<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table des Bordereaux de Soins (BS)
        Schema::create('bordereaux_cnam', function (Blueprint $table) {
            $table->id();
            $table->string('numero_bs')->unique();
            $table->foreignId('dentiste_id')->constrained('users');
            $table->date('date_bordereau');
            $table->decimal('montant_total', 10, 3)->default(0);
            $table->enum('statut', ['brouillon', 'transmis', 'valide', 'rejete', 'paye'])->default('brouillon');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Table des Soins (Actes médicaux)
        Schema::create('soins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('dentiste_id')->constrained('users');
            $table->foreignId('bordereau_id')->nullable()->constrained('bordereaux_cnam')->onDelete('set null');
            $table->string('acte_code'); // Code CNAM (ex: D15)
            $table->string('designation');
            $table->date('date_soin');
            $table->decimal('montant', 10, 3);
            $table->decimal('part_cnam', 10, 3);
            $table->decimal('part_patient', 10, 3);
            $table->enum('statut', ['effectue', 'en_attente_bs', 'inclu_dans_bs'])->default('effectue');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('soins');
        Schema::dropIfExists('bordereaux_cnam');
    }
};
