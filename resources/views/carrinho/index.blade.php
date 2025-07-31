@extends('layouts.app')

@section('title', 'Carrinho de Compras - Montilink Web Store')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Início</a></li>
                <li class="breadcrumb-item active" aria-current="page">Carrinho</li>
            </ol>
        </nav>

        <h1 class="mb-4">Carrinho de Compras</h1>

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

        @if($itens->count() > 0)
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Itens do Carrinho</h5>
                        </div>
                        <div class="card-body">
                            @foreach($itens as $item)
                                <div class="cart-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            @if($item->produto->imagem)
                                                <img src="{{ asset('storage/' . $item->produto->imagem) }}" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;" alt="{{ $item->produto->nome }}">
                                            @else
                                                <img src="https://via.placeholder.com/80x80?text=Sem+Imagem" class="img-fluid rounded" alt="{{ $item->produto->nome }}">
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="mb-1">{{ $item->produto->nome }}</h6>
                                            <small class="text-muted">SKU: {{ $item->produto->sku }}</small>
                                            <br>
                                            <small class="text-muted">Preço unitário: R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</small>
                                        </div>
                                        <div class="col-md-3">
                                            <form action="{{ route('carrinho.atualizar', $item->id) }}" method="POST" class="d-inline" onsubmit="setTimeout(updateCartCount, 500);">
                                                @csrf
                                                @method('PATCH')
                                                <div class="input-group input-group-sm">
                                                    <input type="number" name="quantidade" class="form-control" value="{{ $item->quantidade }}" min="1" max="{{ $item->produto->estoque->quantidade }}">
                                                    <button type="submit" class="btn btn-outline-primary">Atualizar</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <div class="mb-2">
                                                <strong>R$ {{ number_format($item->subtotal, 2, ',', '.') }}</strong>
                                            </div>
                                            <form action="{{ route('carrinho.remover', $item->id) }}" method="POST" class="d-inline" onsubmit="setTimeout(updateCartCount, 500);">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Remover este item do carrinho?')">Remover</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <hr>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Verificação de CEP -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Calcular Frete</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="cep" class="form-label">CEP de Entrega</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="cep" placeholder="00000-000" maxlength="9">
                                    <button class="btn btn-outline-secondary" type="button" id="verificar-cep">Verificar</button>
                                </div>
                                <div class="form-text">Digite o CEP para verificar o endereço de entrega.</div>
                            </div>
                            <div id="endereco-info" class="d-none">
                                <div class="alert alert-info">
                                    <strong>Endereço encontrado:</strong><br>
                                    <span id="endereco-completo"></span>
                                </div>
                            </div>
                            <div id="cep-error" class="d-none">
                                <div class="alert alert-danger">
                                    <span id="error-message"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cupom de Desconto -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Cupom de Desconto</h5>
                        </div>
                        <div class="card-body">
                            @if(isset($cupom))
                                <div class="alert alert-success">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Cupom aplicado:</strong> {{ $cupom['codigo'] }}<br>
                                            <span class="text-success">
                                                @if($cupom['tipo'] === 'percentual')
                                                    Desconto de {{ number_format($cupom['valor'], 0) }}%
                                                @else
                                                    Desconto de R$ {{ number_format($cupom['valor'], 2, ',', '.') }}
                                                @endif
                                            </span>
                                        </div>
                                        <form action="{{ route('carrinho.remover-cupom') }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Remover</button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('carrinho.aplicar-cupom') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="codigo-cupom" class="form-label">Código do Cupom</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="codigo-cupom" name="codigo" placeholder="Digite o código" required>
                                            <button class="btn btn-primary" type="submit">Aplicar</button>
                                        </div>
                                        <div class="form-text">Digite o código do cupom para obter desconto.</div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Resumo do Pedido -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Resumo do Pedido</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span>Subtotal:</span>
                                <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                            </div>
                            
                            @if(isset($cupom) && $desconto > 0)
                                <div class="d-flex justify-content-between mb-3 text-success">
                                    <span>Desconto:</span>
                                    <span>- R$ {{ number_format($desconto, 2, ',', '.') }}</span>
                                </div>
                            @endif
                            
                            <div class="d-flex justify-content-between mb-3">
                                <span>Frete:</span>
                                <span class="text-success">
                                    @if($frete == 0)
                                        <strong>GRÁTIS</strong>
                                    @else
                                        R$ {{ number_format($frete, 2, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                            
                            @if($frete == 0)
                                <div class="alert alert-success py-2 mb-3">
                                    <small><i class="fas fa-truck"></i> Frete grátis para pedidos acima de R$ 200,00!</small>
                                </div>
                            @elseif($frete == 15.00)
                                <div class="alert alert-info py-2 mb-3">
                                    <small><i class="fas fa-info-circle"></i> Frete promocional para pedidos entre R$ 52,00 e R$ 166,59!</small>
                                </div>
                            @endif
                            
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total:</strong>
                                <strong>R$ {{ number_format($total, 2, ',', '.') }}</strong>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button class="btn btn-success btn-lg">Finalizar Compra</button>
                                <a href="{{ url('/') }}" class="btn btn-outline-primary">Continuar Comprando</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <form action="{{ route('carrinho.limpar') }}" method="POST" onsubmit="setTimeout(updateCartCount, 500);">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Tem certeza que deseja limpar todo o carrinho?')">Limpar Carrinho</button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <h3>Seu carrinho está vazio</h3>
                <p class="text-muted">Adicione alguns produtos para começar suas compras!</p>
                <a href="{{ url('/') }}" class="btn btn-primary">Ver Produtos</a>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cepInput = document.getElementById('cep');
            const verificarBtn = document.getElementById('verificar-cep');
            const enderecoInfo = document.getElementById('endereco-info');
            const enderecoCompleto = document.getElementById('endereco-completo');
            const cepError = document.getElementById('cep-error');
            const errorMessage = document.getElementById('error-message');

            // Máscara para CEP
            cepInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 5) {
                    value = value.replace(/(\d{5})(\d)/, '$1-$2');
                }
                e.target.value = value;
            });

            verificarBtn.addEventListener('click', function() {
                const cep = cepInput.value.replace(/\D/g, '');
                
                if (cep.length !== 8) {
                    showError('CEP deve conter 8 dígitos.');
                    return;
                }

                verificarBtn.disabled = true;
                verificarBtn.textContent = 'Verificando...';
                hideMessages();

                fetch('{{ route("carrinho.verificar-cep") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ cep: cep })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const endereco = `${data.data.logradouro}, ${data.data.bairro}<br>${data.data.localidade} - ${data.data.uf}<br>CEP: ${data.data.cep}`;
                        enderecoCompleto.innerHTML = endereco;
                        enderecoInfo.classList.remove('d-none');
                    } else {
                        showError(data.message);
                    }
                })
                .catch(error => {
                    showError('Erro ao verificar CEP. Tente novamente.');
                })
                .finally(() => {
                    verificarBtn.disabled = false;
                    verificarBtn.textContent = 'Verificar';
                });
            });

            function showError(message) {
                errorMessage.textContent = message;
                cepError.classList.remove('d-none');
                enderecoInfo.classList.add('d-none');
            }

            function hideMessages() {
                cepError.classList.add('d-none');
                enderecoInfo.classList.add('d-none');
            }
            
            // Converter código de cupom para maiúsculas
            const codigoCupomInput = document.getElementById('codigo-cupom');
            if (codigoCupomInput) {
                codigoCupomInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }
        });
    </script>
@endsection