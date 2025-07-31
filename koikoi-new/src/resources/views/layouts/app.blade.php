<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'KOIKOI - 恋と出会いのイベントサイト')</title>
    <meta name="description" content="@yield('description', 'アニメコン・街コンなど、様々な出会いのイベントを開催。東京・横浜・大阪など全国で開催中。')">
    
    <!-- OGP -->
    <meta property="og:title" content="@yield('og_title', 'KOIKOI - 恋と出会いのイベントサイト')">
    <meta property="og:description" content="@yield('og_description', 'アニメコン・街コンなど、様々な出会いのイベントを開催')">
    <meta property="og:image" content="@yield('og_image', asset('img/ogp-default.jpg'))">
    <meta property="og:type" content="@yield('og_type', 'website')">
    
    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="@yield('body_class', '')">
    <!-- ヘッダー -->
    <header class="site-header">
        <div class="header-container">
            <div class="header-content">
                <a href="/" class="logo">
                    <img src="{{ asset('img/logo.png') }}" alt="KOIKOI" width="150" height="40">
                </a>
                
                <nav class="main-nav">
                    <ul>
                        <li><a href="/anime/" class="nav-anime">アニメコン</a></li>
                        <li><a href="/machi/" class="nav-machi">街コン</a></li>
                        <li><a href="/events/">全イベント</a></li>
                        <li><a href="/events/calendar/">カレンダー</a></li>
                    </ul>
                </nav>
                
                <div class="header-actions">
                    @auth
                        <a href="/mypage/" class="btn btn-mypage">マイページ</a>
                    @else
                        <a href="/login" class="btn btn-login">ログイン</a>
                        <a href="/register" class="btn btn-register">新規登録</a>
                    @endauth
                </div>
                
                <!-- モバイルメニューボタン -->
                <button class="mobile-menu-toggle" aria-label="メニュー">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>
    
    <!-- モバイルメニュー -->
    <div class="mobile-menu">
        <nav>
            <ul>
                <li><a href="/anime/">アニメコン</a></li>
                <li><a href="/machi/">街コン</a></li>
                <li><a href="/events/">全イベント</a></li>
                <li><a href="/events/calendar/">カレンダー</a></li>
                @auth
                    <li><a href="/mypage/">マイページ</a></li>
                    <li>
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit" class="logout-btn">ログアウト</button>
                        </form>
                    </li>
                @else
                    <li><a href="/login">ログイン</a></li>
                    <li><a href="/register">新規登録</a></li>
                @endauth
            </ul>
        </nav>
    </div>
    
    <!-- メインコンテンツ -->
    <main class="site-main">
        @yield('content')
    </main>
    
    <!-- フッター -->
    <x-footer />
    
    @stack('scripts')
</body>
</html>