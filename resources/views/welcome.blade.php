@extends('layouts.app')

@section('title', 'Início - Montilink Web Store')

@section('content')
    <!-- Banner -->
    <section class="store-banner text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Bem-vindo à Montilink Web Store</h1>
            <p class="lead">Os melhores produtos de tecnologia, informática e acessórios para você!</p>
            <a href="#produtos" class="btn btn-light btn-lg mt-3 fw-semibold">Ver Produtos</a>
        </div>
    </section>

    <!-- Produtos -->
    <section id="produtos" class="container mb-5">
        <h2 class="mb-4 fw-bold text-center">Produtos em Destaque</h2>
        <div class="row g-4">
            @if(isset($produtos) && $produtos->count() > 0)
                @foreach($produtos as $produto)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card product-card h-100">
                        @if($produto->imagem)
                            <img src="{{ asset('storage/' . $produto->imagem) }}" class="card-img-top product-img" alt="{{ $produto->nome }}">
                        @else
                            <img src="https://via.placeholder.com/300x200?text=Sem+Imagem" class="card-img-top product-img" alt="{{ $produto->nome }}">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $produto->nome }}</h5>
                            <p class="card-text">{{ Str::limit($produto->descricao, 100) }}</p>
                            <div class="mt-auto">
                                <span class="fw-bold text-black fs-5">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                                <a href="{{ route('produto.show', $produto->id) }}" class="btn w-100 mt-2" style="background-color: #f70293; color: white;">Ver Produto</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Produtos estáticos de exemplo -->
                <div class="col-12 text-center">
                    <p class="text-muted">Nenhum produto cadastrado ainda.</p>
                    <a href="{{ url('/admin') }}" class="btn btn-primary">Acessar Painel Admin</a>
                </div>
            @endif
        </div>
    </section>

@endsection