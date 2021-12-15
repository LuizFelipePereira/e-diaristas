<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiaristaRequest;
use Illuminate\Http\Request;
use App\Models\Diarista;
use App\Services\ViaCEP;

class DiaristaController extends Controller
{

    protected ViaCEP $viaCep;

    public function __construct(
        ViaCEP $viaCep
    ) {
        $this->viaCep = $viaCep;
    }

    /**
     * Lista as Diaristas
     */
    public function index()
    {

        $diaristas = Diarista::get();
        return view('index', [
            'diaristas' => $diaristas
        ]);
    }

    /**
     * Mostra o Formulário de Criação
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Cria uma diarista no banco de dados
     */
    public function store(DiaristaRequest $request)
    {
        $dados = $request->except('_token');

        if ($request->file('foto_usuario') == null) {
            $dados['foto_usuario'] = "";
        } else {
            $dados['foto_usuario'] = $request->foto_usuario->store('public');
        }

        $dados['cpf'] = str_replace(['.', '-'], '', $dados['cpf']);
        $dados['cep'] = str_replace('-', '', $dados['cep']);
        $dados['telefone'] = str_replace(['(', ')', ' ', '-'], '', $dados['telefone']);
        $dados['codigo_ibge'] = $this->viaCep->buscar($dados['cep'])['ibge'];
        Diarista::create($dados);


        return redirect()->route('diaristas.index');
    }

    /**
     * Mostra o Formulário de edição populado
     */
    public function edit(int $id)
    {
        $diarista = Diarista::findOrFail($id);

        return view('edit', ['diarista' => $diarista]);
    }

    /**
     * Atualiza uma diarista no banco de dados
     */
    public function update(int $id, DiaristaRequest $request)
    {
        $diarista = Diarista::findOrFail($id);

        $dados = $request->except(['_token', '_method']);

        $dados['cpf'] = str_replace(['.', '-'], '', $dados['cpf']);
        $dados['cep'] = str_replace('-', '', $dados['cep']);
        $dados['telefone'] = str_replace(['(', ')', ' ', '-'], '', $dados['telefone']);
        $dados['codigo_ibge'] = $this->viaCEP->buscar($dados['cep'])['ibge'];

        if ($request->hasFile('foto_usuario')) {
            $dados['foto_usuario'] = $request->foto_usuario->store('public');
        }

        $diarista->update($dados);

        return redirect()->route('diaristas.index');
    }

    /**
     * Apaga uma diarista no banco de dados
     */
    public function destroy(int $id)
    {
        $diarista = Diarista::findOrFail($id);
        $diarista->delete();
        return redirect()->route('diaristas.index');
    }
}
