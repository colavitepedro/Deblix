<?php

namespace App\Http\Controllers;

use App\Models\Resposta;
use App\Models\Topico;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TopicoController extends Controller
{
    public function index(Request $request)
    {
        $busca = trim((string) $request->query('busca', ''));
        $categoria = $request->query('categoria');
        $ordenacao = $request->query('ordenacao', 'recentes');

        $topicosQuery = Topico::query()
            ->with('dono')
            ->withCount('respostas');

        if ($busca !== '') {
            $topicosQuery->where(function ($query) use ($busca) {
                $query->where('titulo', 'like', "%{$busca}%")
                    ->orWhere('descricao', 'like', "%{$busca}%")
                    ->orWhere('autor', 'like', "%{$busca}%");
            });
        }

        if (in_array($categoria, Topico::CATEGORIAS, true)) {
            $topicosQuery->where('categoria', $categoria);
        } else {
            $categoria = null;
        }

        match ($ordenacao) {
            'antigos' => $topicosQuery->orderBy('data_criacao')->orderBy('id'),
            'mais_respondidos' => $topicosQuery->orderByDesc('respostas_count')
                ->orderByDesc('data_criacao')
                ->orderByDesc('id'),
            default => $topicosQuery->orderByDesc('data_criacao')->orderByDesc('id'),
        };

        if (! in_array($ordenacao, ['recentes', 'antigos', 'mais_respondidos'], true)) {
            $ordenacao = 'recentes';
        }

        return view('topicos.index', [
            'topicos' => $topicosQuery->paginate(8)->withQueryString(),
            'categorias' => Topico::CATEGORIAS,
            'busca' => $busca,
            'categoriaAtual' => $categoria,
            'ordenacao' => $ordenacao,
            'totalTopicos' => Topico::count(),
            'totalRespostas' => Resposta::count(),
        ]);
    }

    public function create()
    {
        return view('topicos.create', [
            'categorias' => Topico::CATEGORIAS,
            'topico' => new Topico([
                'autor' => auth()->user()->name,
                'data_criacao' => $this->dataAtualSaoPaulo(),
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateTopico($request);
        $validated['user_id'] = auth()->id();
        $validated['autor'] = auth()->user()->name;
        $validated['data_criacao'] = $this->dataAtualSaoPaulo();
        $validated['imagem'] = $this->storeImagem($request);

        $topico = Topico::create($validated);

        return redirect()->route('topicos.show', $topico)
            ->with('success', 'Tópico criado.');
    }

    public function show(Topico $topico)
    {
        $topico->loadCount('respostas')
            ->load('dono')
            ->load(['respostas' => fn ($query) => $query->with('dono')->orderBy('created_at')->orderBy('id')]);

        return view('topicos.show', compact('topico'));
    }

    public function edit(Topico $topico)
    {
        $this->authorize('update', $topico);

        return view('topicos.edit', [
            'topico' => $topico,
            'categorias' => Topico::CATEGORIAS,
        ]);
    }

    public function update(Request $request, Topico $topico)
    {
        $this->authorize('update', $topico);

        $validated = $this->validateTopico($request);

        if ($request->hasFile('imagem')) {
            if ($topico->imagem) {
                Storage::disk('public')->delete($topico->imagem);
            }

            $validated['imagem'] = $this->storeImagem($request);
        }

        $topico->update($validated);

        return redirect()->route('topicos.show', $topico)
            ->with('success', 'Tópico atualizado.');
    }

    public function destroy(Topico $topico)
    {
        $this->authorize('delete', $topico);

        if ($topico->imagem) {
            Storage::disk('public')->delete($topico->imagem);
        }

        $topico->delete();

        return redirect()->route('topicos.index')
            ->with('success', 'Tópico excluído.');
    }

    private function validateTopico(Request $request): array
    {
        return $request->validate([
            'titulo' => ['required', 'string', 'min:3', 'max:255'],
            'categoria' => ['required', Rule::in(Topico::CATEGORIAS)],
            'descricao' => ['required', 'string', 'min:10'],
            'imagem' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], $this->messages());
    }

    private function dataAtualSaoPaulo(): string
    {
        return Carbon::now('America/Sao_Paulo')->toDateString();
    }

    private function storeImagem(Request $request): ?string
    {
        if (! $request->hasFile('imagem')) {
            return null;
        }

        return $request->file('imagem')->store('topicos', 'public');
    }

    private function messages(): array
    {
        return [
            'required' => 'Este campo é obrigatório.',
            'string' => 'Informe um valor textual válido.',
            'min' => 'Informe pelo menos :min caracteres.',
            'max' => 'Informe no máximo :max caracteres.',
            'date' => 'Informe uma data válida.',
            'in' => 'Selecione uma categoria válida.',
            'image' => 'Envie um arquivo de imagem válido.',
            'mimes' => 'A imagem deve ser JPG, JPEG, PNG ou WEBP.',
        ];
    }
}
