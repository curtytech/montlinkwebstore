@extends('layouts.app')

@section('title', $produto->nome . ' - Montilink Web Store')

@section('content')
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Início</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/#produtos') }}">Produtos</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $produto->nome }}</li>
            </ol>
        </nav>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-6 mb-4">
                @if($produto->imagem)
                    @if(str_starts_with($produto->imagem, 'http'))
                        <img src="{{ $produto->imagem }}" class="img-fluid product-image" alt="{{ $produto->nome }}" referrerpolicy="no-referrer">
                    @else
                        <img src="{{ asset('storage/' . $produto->imagem) }}" class="img-fluid product-image" alt="{{ $produto->nome }}">
                    @endif
                @else
                    <img src="https://via.placeholder.com/400x400?text=Sem+Imagem" class="img-fluid product-image" alt="{{ $produto->nome }}">
                @endif
            </div>
            <div class="col-md-6">
                <h1 class="mb-3">{{ $produto->nome }}</h1>
                <p class="text-muted">SKU: {{ $produto->sku }}</p>
                <h2 class="text-success mb-4">R$ {{ number_format($produto->preco, 2, ',', '.') }}</h2>
                
                <div class="mb-4">
                    <h5>Descrição:</h5>
                    <p>{{ $produto->descricao }}</p>
                </div>
                
                @if($produto->estoque && $produto->estoque->quantidade > 0)
                    <p class="text-success"><i class="bi bi-check-circle"></i> Em estoque: {{ $produto->estoque->quantidade }} unidades</p>
                    
                    <form action="{{ route('carrinho.adicionar', $produto->id) }}" method="POST" onsubmit="setTimeout(updateCartCount, 500);">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="quantidade" class="form-label">Quantidade:</label>
                                <input type="number" class="form-control" id="quantidade" name="quantidade" value="1" min="1" max="{{ $produto->estoque->quantidade }}" required>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-lg" style="background-color: #f70293; color: white;">Adicionar ao Carrinho</button>
                            <a href="{{ route('carrinho.index') }}" class="btn" style="color: #f70293; background-color: transparent; border: 1px solid #f70293; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#f70293', this.style.color= 'white' " onmouseout="this.style.backgroundColor='transparent', this.style.color='#f70293'">Ver Carrinho</a>
                        </div>
                    </form>
                @else
                    <p class="text-danger"><i class="bi bi-x-circle"></i> Produto indisponível</p>
                    <div class="d-grid gap-2">
                        <button class="btn btn-secondary btn-lg" disabled>Produto Indisponível</button>
                        <button class="btn btn-outline-primary">Avise-me quando chegar</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection