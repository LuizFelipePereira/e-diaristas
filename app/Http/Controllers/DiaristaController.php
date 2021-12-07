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
        $dados['foto_usuario'] = $request->foto_usuario->store('public');

        Diarista::create($dados);

        return redirect()->route('diaristas.index');
    }
}
