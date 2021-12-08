<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diarista;

class DiaristaController extends Controller
{


    public function index()
    {

        $diaristas = Diarista::get();
        return view('index', [
            'diaristas' => $diaristas
        ]);
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $dados = $request->except('_token');

        if ($request->file('foto_usuario') == null) {
            $dados['foto_usuario'] = "";
        } else {
            $dados['foto_usuario'] = $request->foto_usuario->store('public');
        }



        Diarista::create($dados);

        return redirect()->route('diaristas.index');
    }

    public function edit(int $id)
    {
        $diarista = Diarista::findOrFail($id);

        return view('edit', ['diarista' => $diarista]);
    }
    public function update(int $id, Request $request)
    {
        $diarista = Diarista::findOrFail($id);

        $dados = $request->except(['_token', '_method']);

        if ($request->hasFile('foto_usuario')) {
            $dados['foto_usuario'] = $request->foto_usuario->store('public');
        }

        $diarista->update($dados);

        return redirect()->route('diaristas.index');
    }
}
