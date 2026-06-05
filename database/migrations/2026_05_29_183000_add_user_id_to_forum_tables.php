<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('topicos', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();
        });

        Schema::table('respostas', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();
        });

        $this->backfillTopicos();
        $this->backfillRespostas();
    }

    public function down(): void
    {
        Schema::table('respostas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('topicos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }

    private function backfillTopicos(): void
    {
        DB::table('topicos')
            ->select(['id', 'autor'])
            ->whereNull('user_id')
            ->orderBy('id')
            ->chunkById(100, function ($topicos) {
                foreach ($topicos as $topico) {
                    $userId = DB::table('users')
                        ->where('name', $topico->autor)
                        ->orderBy('id')
                        ->value('id');

                    if ($userId) {
                        DB::table('topicos')
                            ->where('id', $topico->id)
                            ->update(['user_id' => $userId]);
                    }
                }
            });
    }

    private function backfillRespostas(): void
    {
        DB::table('respostas')
            ->select(['id', 'usuario'])
            ->whereNull('user_id')
            ->orderBy('id')
            ->chunkById(100, function ($respostas) {
                foreach ($respostas as $resposta) {
                    $userId = DB::table('users')
                        ->where('name', $resposta->usuario)
                        ->orderBy('id')
                        ->value('id');

                    if ($userId) {
                        DB::table('respostas')
                            ->where('id', $resposta->id)
                            ->update(['user_id' => $userId]);
                    }
                }
            });
    }
};
