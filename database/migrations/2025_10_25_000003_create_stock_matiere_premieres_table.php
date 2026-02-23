<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_matiere_premieres', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->decimal('quantite', 10, 2)->default(0);
            $table->decimal('seuil_alerte', 10, 2)->default(5);
            $table->string('unite_mesure')->default('unité');
            $table->foreignId('fournisseur_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_matiere_premieres');
    }
};
