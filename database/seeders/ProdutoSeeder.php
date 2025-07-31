<?php

namespace Database\Seeders;

use App\Models\Produto;
use App\Models\Estoque;
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        $produtos = [
            [
                'nome' => 'Notebook Dell Inspiron 15',
                'descricao' => 'Notebook Dell Inspiron 15 com processador Intel Core i5, 8GB RAM, SSD 256GB',
                'preco' => 2499.99,
                'sku' => 'DELL-INS-15-001',
                'ativo' => true,
                'estoque' => ['quantidade' => 10, 'quantidade_minima' => 2, 'localizacao' => 'A1-01']
            ],
            [
                'nome' => 'Mouse Gamer Logitech G502',
                'descricao' => 'Mouse gamer com sensor óptico de alta precisão, 11 botões programáveis',
                'preco' => 299.99,
                'sku' => 'LOG-G502-001',
                'ativo' => true,
                'estoque' => ['quantidade' => 25, 'quantidade_minima' => 5, 'localizacao' => 'B2-15']
            ],
            [
                'nome' => 'Teclado Mecânico Corsair K70',
                'descricao' => 'Teclado mecânico com switches Cherry MX Red, iluminação RGB',
                'preco' => 599.99,
                'sku' => 'COR-K70-001',
                'ativo' => true,
                'estoque' => ['quantidade' => 15, 'quantidade_minima' => 3, 'localizacao' => 'B2-10']
            ]
        ];

        foreach ($produtos as $produtoData) {
            $estoqueData = $produtoData['estoque'];
            unset($produtoData['estoque']);
            
            $produto = Produto::create($produtoData);
            $produto->estoque()->create($estoqueData);
        }
    }
}