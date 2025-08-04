<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'KOIKOI - 日本最大級のイベント情報サイト')</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', 'アニメコン、街コンなど、日本全国の出会いと交流イベント情報が満載。あなたにぴったりのイベントを見つけよう！')">
    <meta name="keywords" content="@yield('keywords', 'イベント,アニメコン,街コン,出会い,交流,パーティー,婚活,KOIKOI')">
    
    <!-- OGP -->
    <meta property="og:title" content="@yield('og_title', 'KOIKOI - 日本最大級のイベント情報サイト')">
    <meta property="og:description" content="@yield('og_description', 'アニメコン、街コンなど、日本全国の出会いと交流イベント情報が満載')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/ogp-default.jpg'))">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('build/assets/app-CWncv8dV.css') }}" rel="stylesheet">
    
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="KOIKOI" height="40" class="d-none">
                    <span class="h3 mb-0 text-primary fw-bold">KOIKOI</span>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="navbar-collapse" id="navbarMain">
                    <!-- メインナビゲーション（常に表示） -->
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') && !request('category') ? 'active' : '' }}" 
                               href="{{ route('home') }}">
                                <i class="fas fa-th me-1"></i>
                                <span class="fw-bold">全イベント</span>
                            </a>
                        </li>
                        <li class="nav-item mx-2">
                            <span class="nav-link disabled">|</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->get('category') === 'anime' || request()->routeIs('anime.*') ? 'active' : '' }}" 
                               href="{{ route('anime.index') }}">
                                <x-theme-icon type="anime" class="me-1" />
                                <span class="fw-bold">アニメコン</span>
                            </a>
                        </li>
                        <li class="nav-item mx-2">
                            <span class="nav-link disabled">|</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->get('category') === 'machi' || request()->routeIs('machi.*') ? 'active' : '' }}" 
                               href="{{ route('machi.index') }}">
                                <x-theme-icon type="machi" class="me-1" />
                                <span class="fw-bold">街コン</span>
                            </a>
                        </li>
                    </ul>
                    
                    <!-- ログインボタン -->
                    <div class="ms-auto">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-sign-in-alt me-1"></i>
                            ログイン
                        </a>
                        <a href="#" class="btn btn-primary btn-sm ms-2">
                            <i class="fas fa-user-plus me-1"></i>
                            新規登録
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        
    </header>
    
    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">KOIKOI について</h5>
                    <p class="small">
                        日本全国のイベント情報を提供する国内最大級のイベントポータルサイト。
                        アニメコン、街コンなど、様々な出会いと交流の場を提供しています。
                    </p>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">イベントカテゴリ</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('home') }}">全イベント</a></li>
                        <li class="mb-2"><a href="{{ route('anime.index') }}">アニメコン</a></li>
                        <li class="mb-2"><a href="{{ route('machi.index') }}">街コン</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">サポート</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('contact') }}">お問い合わせ</a></li>
                        <li class="mb-2"><a href="{{ route('terms') }}">利用規約</a></li>
                        <li class="mb-2"><a href="{{ route('privacy') }}">プライバシーポリシー</a></li>
                        <li class="mb-2"><a href="{{ route('company') }}">運営会社</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">人気エリア</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('area.prefecture', 'tokyo') }}">東京</a></li>
                        <li class="mb-2"><a href="{{ route('area.prefecture', 'osaka') }}">大阪</a></li>
                        <li class="mb-2"><a href="{{ route('area.prefecture', 'kanagawa') }}">神奈川</a></li>
                        <li class="mb-2"><a href="{{ route('area.prefecture', 'aichi') }}">愛知</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">フォロー</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white fs-5"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white fs-5"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white fs-5"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white fs-5"><i class="fab fa-line"></i></a>
                    </div>
                </div>
            </div>
            
            <hr class="border-secondary my-4">
            
            <div class="text-center small">
                <p class="mb-0">&copy; {{ date('Y') }} KOIKOI. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('build/assets/app-C0G0cght.js') }}" type="module"></script>
    
    @stack('scripts')
</body>
</html>