<?php

namespace App\Http\Controllers;

use App\Models\Carrinho;
use App\Models\Produto;
use App\Models\Cupom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class CarrinhoController extends Controller
{
    /**
     * Exibe o carrinho de compras.
     */
    public function index()
    {
        $itens = $this->getCarrinhoItens();
        $subtotal = $itens->sum('subtotal');
        
        // Recuperar cupom da sessão, se existir
        $cupom = Session::get('cupom');
        $desconto = 0;
        
        if ($cupom) {
            $desconto = $cupom['desconto'];
        }
        
        $frete = $this->calcularFrete($subtotal);
        $total = $subtotal + $frete - $desconto;
        
        return view('carrinho.index', compact('itens', 'subtotal', 'frete', 'total', 'cupom', 'desconto'));
    }
    
    /**
     * Adiciona um produto ao carrinho.
     */
    public function adicionar(Request $request, $produtoId)
    {
        $request->validate([
            'quantidade' => 'required|integer|min:1'
        ]);

        $produto = Produto::with('estoque')->findOrFail($produtoId);
        
        // Verificar se há estoque suficiente
        if (!$produto->estoque || $produto->estoque->quantidade < $request->quantidade) {
            return back()->with('error', 'Estoque insuficiente para este produto.');
        }

        $sessionId = Session::getId();
        $userId = Auth::id();

        // Verificar se o item já existe no carrinho
        $itemExistente = Carrinho::where(function($query) use ($sessionId, $userId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->where('produto_id', $produtoId)
            ->first();

        if ($itemExistente) {
            // Atualizar quantidade
            $novaQuantidade = $itemExistente->quantidade + $request->quantidade;
            
            if ($produto->estoque->quantidade < $novaQuantidade) {
                return back()->with('error', 'Estoque insuficiente para esta quantidade.');
            }
            
            $itemExistente->update([
                'quantidade' => $novaQuantidade
            ]);
        } else {
            // Criar novo item no carrinho
            Carrinho::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'produto_id' => $produtoId,
                'quantidade' => $request->quantidade,
                'preco_unitario' => $produto->preco,
            ]);
        }

        return back()->with('success', 'Produto adicionado ao carrinho!');
    }

    /**
     * Atualiza a quantidade de um item no carrinho.
     */
    public function atualizar(Request $request, $itemId)
    {
        $request->validate([
            'quantidade' => 'required|integer|min:1'
        ]);

        $item = $this->getCarrinhoItem($itemId);
        
        if (!$item) {
            return back()->with('error', 'Item não encontrado no carrinho.');
        }

        // Verificar estoque
        if ($item->produto->estoque->quantidade < $request->quantidade) {
            return back()->with('error', 'Estoque insuficiente para esta quantidade.');
        }

        $item->update([
            'quantidade' => $request->quantidade
        ]);

        return back()->with('success', 'Carrinho atualizado!');
    }

    /**
     * Remove um item do carrinho.
     */
    public function remover($itemId)
    {
        $item = $this->getCarrinhoItem($itemId);
        
        if (!$item) {
            return back()->with('error', 'Item não encontrado no carrinho.');
        }

        $item->delete();

        return back()->with('success', 'Item removido do carrinho!');
    }

    /**
     * Limpa todo o carrinho.
     */
    public function limpar()
    {
        $sessionId = Session::getId();
        $userId = Auth::id();

        Carrinho::where(function($query) use ($sessionId, $userId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->delete();

        return back()->with('success', 'Carrinho limpo!');
    }

    /**
     * Conta o número de itens no carrinho.
     */
    public function contar()
    {
        $count = $this->getCarrinhoItens()->sum('quantidade');
        return response()->json(['count' => $count]);
    }

    /**
     * Calcula o frete baseado no subtotal.
     */
    private function calcularFrete($subtotal)
    {
        if ($subtotal >= 200.00) {
            return 0; // Frete grátis
        } elseif ($subtotal >= 52.00 && $subtotal <= 166.59) {
            return 15.00;
        } else {
            return 20.00;
        }
    }

    /**
     * Verifica CEP usando a API do ViaCEP.
     */
    public function verificarCep(Request $request)
    {
        $request->validate([
            'cep' => 'required|string|size:8'
        ]);

        $cep = preg_replace('/[^0-9]/', '', $request->cep);
        
        if (strlen($cep) !== 8) {
            return response()->json([
                'success' => false,
                'message' => 'CEP deve conter exatamente 8 dígitos.'
            ], 400);
        }

        try {
            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['erro'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'CEP não encontrado.'
                    ], 404);
                }
                
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao consultar CEP.'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de conexão com o serviço de CEP.'
            ], 500);
        }
    }

    /**
     * Obtém os itens do carrinho atual.
     */
    private function getCarrinhoItens()
    {
        $sessionId = Session::getId();
        $userId = Auth::id();

        return Carrinho::with(['produto', 'produto.estoque'])
            ->where(function($query) use ($sessionId, $userId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->get();
    }

    /**
     * Obtém um item específico do carrinho.
     */
    private function getCarrinhoItem($itemId)
    {
        $sessionId = Session::getId();
        $userId = Auth::id();

        return Carrinho::with(['produto', 'produto.estoque'])
            ->where('id', $itemId)
            ->where(function($query) use ($sessionId, $userId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->first();
    }

    /**
     * Aplica um cupom de desconto ao carrinho.
     */
    public function aplicarCupom(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:50'
        ]);
        
        $codigo = strtoupper($request->codigo);
        $cupom = Cupom::where('codigo', $codigo)->first();
        
        if (!$cupom) {
            return back()->with('error', 'Cupom não encontrado.');
        }
        
        if (!$cupom->isValid()) {
            return back()->with('error', 'Este cupom não é válido ou já expirou.');
        }
        
        $itens = $this->getCarrinhoItens();
        $subtotal = $itens->sum('subtotal');
        
        // Verificar valor mínimo
        if ($cupom->valor_minimo && $subtotal < $cupom->valor_minimo) {
            return back()->with('error', "Este cupom só é válido para compras acima de R$ " . number_format($cupom->valor_minimo, 2, ',', '.'));
        }
        
        // Calcular desconto
        $desconto = $cupom->calcularDesconto($subtotal);
        
        // Salvar na sessão
        Session::put('cupom', [
            'id' => $cupom->id,
            'codigo' => $cupom->codigo,
            'desconto' => $desconto,
            'tipo' => $cupom->tipo,
            'valor' => $cupom->valor
        ]);
        
        return back()->with('success', 'Cupom aplicado com sucesso!');
    }
    
    /**
     * Remove o cupom aplicado.
     */
    public function removerCupom()
    {
        Session::forget('cupom');
        return back()->with('success', 'Cupom removido.');
    }
}