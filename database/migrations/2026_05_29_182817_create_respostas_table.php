<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respostas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('topico_id');
            $table->string('usuario');
            $table->text('mensagem');
            $table->date('data_resposta');
            $table->timestamps();

            $table->foreign('topico_id')
                ->references('id')
                ->on('topicos')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respostas');
    }
};
