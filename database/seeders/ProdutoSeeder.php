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
                'nome' => 'Camisa Polo Masculina Azul',
                'descricao' => 'Camisa polo masculina em algodão, cor azul marinho, tamanhos P ao GG',
                'preco' => 89.90,
                'sku' => 'CAM-POLO-AZ-001',
                'imagem' => 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcQ1uyw2kAa266Xdj-cChs708fnPJ0aLiqEv58qm376wRuXzjjlKWoFUX2m5IFNF83ScOzJqtYgF7xWZXXbtM7vFdlwQ52ZE7RTILjPsxTnVEeM-9BoboqMyl_zFVZ3V8SgNyoDLWiaAEQ&usqp=CAc',
                'ativo' => true,
                'estoque' => ['quantidade' => 50, 'quantidade_minima' => 10, 'localizacao' => 'A1-01']
            ],
            [
                'nome' => 'Camisa Social Branca Slim',
                'descricao' => 'Camisa social masculina branca, corte slim fit, tecido anti-rugas',
                'preco' => 129.90,
                'sku' => 'CAM-SOC-BR-001',
                'imagem' => 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcSgFTp7tjvd_xuIdBlJaJMb7S3y-c8TSCoq1pKxD69ZnpTCdnVC5cBxZFis46OPLLMYrUs6rU9Rx_85MP_mmrlhtrhvYJmGa4-XtYSfkZxSPpRrA2CbpZel4Q&usqp=CAc',
                'ativo' => true,
                'estoque' => ['quantidade' => 30, 'quantidade_minima' => 5, 'localizacao' => 'A1-02']
            ],
            [
                'nome' => 'Camisa Casual Xadrez',
                'descricao' => 'Camisa casual masculina xadrez azul e branco, manga longa',
                'preco' => 79.90,
                'sku' => 'CAM-CAS-XA-001',
                'imagem' => 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRcHMK_phpDYmWICLzsT8Bg4OGnT46JuzAVnXd5CFCGi7ZDJOkQ3voxDviJyGfl9B8m8a9hhLwSwMfSwCClL07tnLGX4hmmlu4oluCUJ37k0g7tSAS6OizW7hq7sqUf-sZd4bkbgA&usqp=CAc',
                'ativo' => true,
                'estoque' => ['quantidade' => 40, 'quantidade_minima' => 8, 'localizacao' => 'A1-03']
            ],
            [
                'nome' => 'Short Jeans Masculino',
                'descricao' => 'Short jeans masculino azul escuro, com elastano, tamanhos 38 ao 48',
                'preco' => 69.90,
                'sku' => 'SHO-JEA-AZ-001',
                'imagem' => 'data:image/webp;base64,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',
                'ativo' => true,
                'estoque' => ['quantidade' => 35, 'quantidade_minima' => 7, 'localizacao' => 'B1-01']
            ],
            [
                'nome' => 'Short Tactel Esportivo',
                'descricao' => 'Short esportivo em tactel, ideal para exercícios, cor preta com detalhes',
                'preco' => 49.90,
                'sku' => 'SHO-TAC-PR-001',
                'imagem' => 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcR5ET5fft5h2ecyeg29v08tOZfMFXKqumrJRHMk6haQMQoFmJAt9R-GpQHZQcH7_qd1cI8ImLAaBLVLhjpv8iXC8H9-5PXzOcyyPm-KBf2r4_LJ8swsvzRabw&usqp=CAc',
                'ativo' => true,
                'estoque' => ['quantidade' => 60, 'quantidade_minima' => 12, 'localizacao' => 'B1-02']
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