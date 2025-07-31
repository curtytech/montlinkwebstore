<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    /**
     * Exibe a página inicial com os produtos.
     */
    public function index()
    {
        $produtos = Produto::with('estoque')
            ->where('ativo', true)
            ->latest()
            ->get();
            
        return view('welcome', compact('produtos'));
    }
    
    /**
     * Exibe a página de detalhes de um produto.
     */
    public function show($id)
    {
        $produto = Produto::with('estoque')->findOrFail($id);
        
        return view('produto', compact('produto'));
    }
}