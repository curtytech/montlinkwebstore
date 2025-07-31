<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cupons', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->enum('tipo', ['percentual', 'valor_fixo']);
            $table->decimal('valor', 10, 2);
            $table->decimal('valor_minimo', 10, 2)->nullable();
            $table->integer('quantidade')->nullable();
            $table->integer('quantidade_usada')->default(0);
            $table->dateTime('data_inicio');
            $table->dateTime('data_fim');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupons');
    }
};