<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('resposta_votos');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('personal_access_tokens');

        if (Schema::hasColumn('topicos', 'fixado')) {
            Schema::table('topicos', function (Blueprint $table) {
                $table->dropIndex(['fixado', 'data_criacao']);
            });
        }

        if (Schema::hasColumn('topicos', 'status')) {
            Schema::table('topicos', function (Blueprint $table) {
                $table->dropIndex(['status']);
            });
        }

        $colunasTopicos = collect(['status', 'fixado', 'visualizacoes'])
            ->filter(fn (string $coluna) => Schema::hasColumn('topicos', $coluna))
            ->all();

        if ($colunasTopicos !== []) {
            Schema::table('topicos', function (Blueprint $table) use ($colunasTopicos) {
                $table->dropColumn($colunasTopicos);
            });
        }

        if (Schema::hasColumn('users', 'is_blocked')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_blocked');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_blocked')->default(false)->after('is_admin');
        });

        Schema::table('topicos', function (Blueprint $table) {
            $table->string('status', 20)->default('aberto')->after('categoria');
            $table->boolean('fixado')->default(false)->after('status');
            $table->unsignedInteger('visualizacoes')->default(0)->after('fixado');
            $table->index(['fixado', 'data_criacao']);
            $table->index('status');
        });

        Schema::create('resposta_votos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('resposta_id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['resposta_id', 'user_id']);
            $table->foreign('resposta_id')->references('id')->on('respostas')->cascadeOnDelete();
        });
    }
};
