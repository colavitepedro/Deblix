<?php

namespace App\Http\Controllers;

use App\Models\Resposta;
use App\Models\Topico;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RespostaController extends Controller
{
    public function store(Request $request, Topico $topico)
    {
        $this->authorize('responder', $topico);

        $validated = $this->validateResposta($request);
        $validated['user_id'] = auth()->id();
        $validated['usuario'] = auth()->user()->name;
        $validated['data_resposta'] = $this->dataAtualSaoPaulo();

        $topico->respostas()->create($validated);

        return redirect()->route('topicos.show', $topico)
            ->with('success', 'Comentário publicado.');
    }

    public function edit(Resposta $resposta)
    {
        $this->authorize('update', $resposta);
        $resposta->load('topico');

        return view('respostas.edit', [
            'resposta' => $resposta,
        ]);
    }

    public function update(Request $request, Resposta $resposta)
    {
        $this->authorize('update', $resposta);

        $validated = $this->validateResposta($request);
        $resposta->update($validated);

        return redirect()->route('topicos.show', $resposta->topico)
            ->with('success', 'Comentário atualizado.');
    }

    public function destroy(Resposta $resposta)
    {
        $this->authorize('delete', $resposta);

        $topico = $resposta->topico;
        $resposta->delete();

        return redirect()->route('topicos.show', $topico)
            ->with('success', 'Comentário excluído.');
    }

    private function validateResposta(Request $request): array
    {
        return $request->validate([
            'mensagem' => ['required', 'string', 'min:2', 'max:5000'],
        ], [
            'required' => 'Este campo é obrigatório.',
            'string' => 'Informe um valor textual válido.',
            'min' => 'Informe pelo menos :min caracteres.',
            'max' => 'Informe no máximo :max caracteres.',
        ]);
    }

    private function dataAtualSaoPaulo(): string
    {
        return Carbon::now('America/Sao_Paulo')->toDateString();
    }
}
