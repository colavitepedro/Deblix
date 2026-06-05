<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topicos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->string('categoria');
            $table->string('autor');
            $table->text('descricao');
            $table->date('data_criacao');
            $table->string('imagem')->nullable();
            $table->timestamps();

            $table->index('categoria');
            $table->index('data_criacao');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topicos');
    }
};
