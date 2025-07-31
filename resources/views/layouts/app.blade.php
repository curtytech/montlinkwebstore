<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="no-referrer">
    <meta http-equiv="Content-Security-Policy" content="img-src 'self' data: https: http:;">
    <title>@yield('title', 'Montilink Web Store')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            padding-top: 76px; /* Espaço para navbar fixa */
        }
        
        /* Header Styles */
        .main-navbar {
            /* background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); */
            backdrop-filter: blur(10px);
            border-bottom: 3px solid;
            border-image: linear-gradient(90deg,rgb(0, 0, 0) 0%, #f70293 100%) 1;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }
        
        .navbar-brand-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .navbar-brand-logo:hover {
            transform: scale(1.05);
        }     
        
        .brand-text {
            color: #fff;
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
        }
        
        .brand-subtitle {
            color: #f70293;
            font-size: 0.75rem;
            font-weight: 500;
            margin: 0;
            line-height: 1;
        }
        
        .navbar-nav .nav-link {
            color: #ccc !important;
            font-weight: 500;
            padding: 0.75rem 1rem !important;
            border-radius: 6px;
            margin: 0 2px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .navbar-nav .nav-link:hover {
            color: #f70293 !important;
            /* background: rgba(248, 184, 3, 0.1); */
            transform: translateY(-1px);
        }
        
        .navbar-nav .nav-link.active {
            color: #f70293 !important;
            /* background: rgba(248, 184, 3, 0.15); */
        }
        
        .navbar-nav .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 2px;
            background: linear-gradient(90deg, #f70293 0%, #f70293 100%);
            border-radius: 2px;
        }
        
        .cart-link {
            position: relative;
            /* background: rgba(248, 184, 3, 0.1) !important; */
            border: 1px solid #ccc
        }
        
        .cart-link:hover {
            /* background: rgba(248, 184, 3, 0.2) !important; */
            border-color: #f70293;
        }
        
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(45deg, #f70293 0%, #f70293 100%);
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(245, 48, 3, 0.4);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .navbar-toggler {
            border: 1px solid #f70293;
            padding: 0.5rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(248, 184, 3, 0.25);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23f8b803' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        .dropdown-menu {
            background: #2d2d2d;
            border: 1px solid rgba(248, 184, 3, 0.3);
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        
        .dropdown-item {
            color: #ccc;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background: rgba(248, 184, 3, 0.1);
            color: #f70293;
        }
        
        .dropdown-divider {
            border-color: rgba(248, 184, 3, 0.3);
        }
        
        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .brand-text {
                font-size: 1.3rem;
            }
            .logo-icon {
                width: 35px;
                height: 35px;
                font-size: 18px;
            }
        }
        
    .store-banner {
        background-image: url('../img/hero.jpg'); /* Add your banner image path */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
        color: #fff;
        padding: 4rem 0;
        margin-bottom: 2rem;
        min-height: 300px;
        display: flex;
        align-items: center;
    }

    .store-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, rgba(64, 17, 73, 0.8) 0%, rgba(248, 3, 236, 0.29) 100%);
        z-index: 1;
    }

    .store-banner > * {
        position: relative;
        z-index: 2;
    }
    .product-card {
        transition: box-shadow 0.2s;
    }
    .product-card:hover {
        box-shadow: 0 4px 24px rgba(0,0,0,0.12);
    }
    .footer {
         background: linear-gradient(170deg,rgb(255, 255, 255) 0%,rgb(255, 255, 255) 100%);
        color: #fff;
        padding: 3rem 0 1rem 0;
        margin-top: 3rem;
        position: relative;
        overflow: hidden;
    }
    .footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #f70293  0%, #000 100%);
    }
    .footer-section h5 {
        color: #000;
        font-weight: 600;
        margin-bottom: 1.5rem;
        position: relative;
    }
    .footer-section h5::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 30px;
        height: 2px;
        background: linear-gradient(90deg, #f70293  0%, #000 100%);
    }
    .footer-link {
        color: #000;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        margin-bottom: 0.5rem;
    }
    .footer-link:hover {
        color: #f70293;
        transform: translateX(5px);
    }
    .social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        background:rgba(247, 2, 149, 0.29) ;
        border: 1px solid #000;
        border-radius: 50%;
        color: #000;
        text-decoration: none;
        margin: 0 10px 10px 0;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    .social-icon:hover {
        background: linear-gradient(45deg, #f70293  0%, #000 100%);
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(245, 48, 3, 0.4);
        border-color: transparent;
    }
    .social-icon.instagram:hover {
        background: linear-gradient(45deg, #f70293  0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);
    }
    .social-icon.youtube:hover {
        background: #ff0000;
    }
    .footer-bottom {
        border-top: 1px solid #f70293;
        margin-top: 2rem;
        padding-top: 1.5rem;
    }
    .montink-logo {
        color: #000;
        font-weight: 700;
        font-size: 1.2rem;
    }
    .product-img {
        height: 200px;
        object-fit: cover;
    }
    .product-image {
        max-height: 400px;
        object-fit: contain;
    }
    .cart-item {
        border-bottom: 1px solid #dee2e6;
        padding: 1rem 0;
    }
    .cart-item:last-child {
        border-bottom: none;
    }
    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    @yield('styles')
</style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg main-navbar">
        <div class="container">
            <!-- Logo e Brand -->
            <a class="navbar-brand-logo" href="{{ url('/') }}">
                <div class="logo-icon">
                <img src="{{ asset('img/logo.png') }}" alt="" style="width: 200px">
                </div>           
            </a>
            
            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="bi bi-house me-1"></i>Início
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('produto*') ? 'active' : '' }}" href="{{ url('/#produtos') }}">
                            <i class="bi bi-box me-1"></i>Produtos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link cart-link position-relative" href="{{ route('carrinho.index') }}">
                            <i class="bi bi-cart me-1"></i>Carrinho
                            <span class="cart-badge" id="cart-count" style="display: none;">0</span>
                        </a>
                    </li>                   
                    
                    <!-- Divider -->
                    <li class="nav-item d-none d-lg-block">
                        <div style="width: 1px; height: 30px; background: rgba(248, 184, 3, 0.3); margin: 0 10px;"></div>
                    </li>
                    
                    <!-- User Menu -->
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <div class="bg-black rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <i class="bi bi-person-fill text-white"></i>
                                    </div>
                                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ url('/admin') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Painel Admin
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Sair
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-person-circle me-1"></i>Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-section">
                        <h5><i class="bi bi-info-circle me-2"></i>Sobre a Montink</h5>
                        <p class="text-black mb-3">Somos uma plataforma de Print On Demand, onde pessoas ou empresas conseguem ter seus produtos personalizados com suas estampas, vender e lucrar muito.</p>
                        <a href="https://montink.com/" target="_blank" class="footer-link">
                            <i class="bi bi-globe me-2"></i>Visite nosso site principal
                        </a>
                    </div>
                </div>
                
                <!-- Links Rápidos -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-section">
                        <h5><i class="bi bi-link-45deg me-2"></i>Links Rápidos</h5>
                        <div class="d-flex flex-column">
                            <a href="{{ url('/') }}" class="footer-link">
                                <i class="bi bi-house me-2"></i>Início
                            </a>
                            <a href="{{ url('/#produtos') }}" class="footer-link">
                                <i class="bi bi-box me-2"></i>Produtos
                            </a>
                            <a href="{{ route('carrinho.index') }}" class="footer-link">
                                <i class="bi bi-cart me-2"></i>Carrinho
                            </a>
                            <a href="{{ url('/#contato') }}" class="footer-link">
                                <i class="bi bi-envelope me-2"></i>Contato
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Redes Sociais -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <h5><i class="bi bi-share me-2"></i>Siga-nos</h5>
                        <p class="text-black mb-3">Conecte-se conosco nas redes sociais e fique por dentro das novidades!</p>
                        <div class="social-links">
                            <a href="https://www.instagram.com/soumontink/" target="_blank" class="social-icon instagram" title="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="https://www.youtube.com/@montinkoficial" target="_blank" class="social-icon youtube" title="YouTube">
                                <i class="bi bi-youtube"></i>
                            </a>
                            <a href="https://montink.com/" target="_blank" class="social-icon" title="Site Oficial">
                                <i class="bi bi-globe"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Trabalhe Conosco -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <h5><i class="bi bi-people me-2"></i>Trabalhe Conosco</h5>
                        <p class="text-black mb-3">Se você é apaixonado por desafios e inovação, queremos você no nosso time!</p>
                        <a href="https://montink.com/" target="_blank" class="btn footer-link btn-sm">
                            <i class="bi bi-briefcase me-2"></i>Ver Oportunidades
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom text-center">
                <div class="row align-items-center">
                    <div class="col-md-6 text-md-start">
                        <p class="mb-1 text-black  ">
                            &copy; {{ date('Y') }} <span class="montink-logo">Montink</span> Web Store. Todos os direitos reservados.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <small class="text-muted">
                            <i class="bi bi-code-slash me-1"></i>Desenvolvido com Laravel & Bootstrap
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Cart Counter Script -->
    <script>
        // Função para atualizar contador do carrinho
        async function updateCartCount() {
            try {
                const response = await fetch('{{ route('carrinho.contar') }}');
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                const cartBadge = document.getElementById('cart-count');
                
                if (!cartBadge) {
                    console.warn('Cart badge element not found');
                    return;
                }

                cartBadge.style.display = data.count > 0 ? 'flex' : 'none';
                if (data.count > 0) {
                    cartBadge.textContent = data.count;
                }
            } catch (error) {
                console.error('Error updating cart count:', error);
            }
        }

        // Atualizar contador quando a página carrega
        document.addEventListener('DOMContentLoaded', updateCartCount);
        
        // Atualizar contador a cada 30 segundos
        setInterval(updateCartCount, 30000);
    </script>
    
    @yield('scripts')
</body>
</html>