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
        Schema::create('formularios', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->enum('categoria', ['geral', 'suporte', 'sugestao', 'reclamacao']);
            $table->enum('prioridade', ['baixa', 'media', 'alta']);
            $table->enum('status', ['pendente', 'em_andamento', 'resolvido', 'rejeitado'])->default('pendente');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('resposta_admin')->nullable();
            $table->foreignId('respondido_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('respondido_em')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formularios');
    }
};

