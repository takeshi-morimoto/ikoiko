<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', '管理画面') - KOIKOI 管理システム</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <link href="{{ asset('build/assets/admin-D0mbUumA.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="admin-header d-flex align-items-center px-3">
        <button class="btn btn-link text-white d-md-none me-3" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        
        <h4 class="mb-0 me-auto">
            <i class="fas fa-chart-line me-2"></i>
            KOIKOI 管理システム
        </h4>
        
        <div class="dropdown">
            <button class="btn btn-link text-white dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-user-circle me-1"></i>
                管理者
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>プロフィール</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>設定</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i>ログアウト</a></li>
            </ul>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="admin-sidebar" id="sidebar">
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('home') }}" target="_blank">
                    <i class="fas fa-home"></i>
                    サイトを見る
                </a>
            </li>
            
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    ダッシュボード
                </a>
            </li>
            
            <li>
                <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    イベント管理
                </a>
            </li>
            
            <li>
                <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#operationsMenu">
                    <i class="fas fa-cogs"></i>
                    イベント運営
                    <i class="fas fa-chevron-down float-end mt-1"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.operations.*') ? 'show' : '' }}" id="operationsMenu">
                    <ul class="sidebar-nav ms-3">
                        <li><a href="#"><i class="fas fa-clock"></i>スケジュール管理</a></li>
                        <li><a href="#"><i class="fas fa-box"></i>備品管理</a></li>
                        <li><a href="#"><i class="fas fa-users"></i>役割分担</a></li>
                        <li><a href="#"><i class="fas fa-chair"></i>座席管理</a></li>
                        <li><a href="#"><i class="fas fa-clipboard-check"></i>チェックリスト</a></li>
                    </ul>
                </div>
            </li>
            
            <li>
                <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#analyticsMenu">
                    <i class="fas fa-chart-bar"></i>
                    分析・レポート
                    <i class="fas fa-chevron-down float-end mt-1"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.analytics.*') ? 'show' : '' }}" id="analyticsMenu">
                    <ul class="sidebar-nav ms-3">
                        <li><a href="{{ route('admin.analytics.dashboard') }}"><i class="fas fa-chart-line"></i>分析ダッシュボード</a></li>
                        <li><a href="{{ route('admin.analytics.events') }}"><i class="fas fa-calendar-check"></i>イベント分析</a></li>
                        <li><a href="{{ route('admin.analytics.customers') }}"><i class="fas fa-user-friends"></i>顧客分析</a></li>
                        <li><a href="{{ route('admin.analytics.revenue') }}"><i class="fas fa-yen-sign"></i>売上分析</a></li>
                    </ul>
                </div>
            </li>
            
            <li>
                <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#staffMenu">
                    <i class="fas fa-user-tie"></i>
                    スタッフ管理
                    <i class="fas fa-chevron-down float-end mt-1"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.staff.*') ? 'show' : '' }}" id="staffMenu">
                    <ul class="sidebar-nav ms-3">
                        <li><a href="{{ route('admin.staff.index') }}"><i class="fas fa-users"></i>スタッフ一覧</a></li>
                        <li><a href="{{ route('admin.staff.shifts') }}"><i class="fas fa-calendar-week"></i>シフト管理</a></li>
                        <li><a href="{{ route('admin.staff.shift-requests') }}"><i class="fas fa-hand-paper"></i>シフト希望</a></li>
                        <li><a href="{{ route('admin.staff.work-records') }}"><i class="fas fa-clock"></i>勤務記録</a></li>
                    </ul>
                </div>
            </li>
            
            <li>
                <a href="#" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    システム設定
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="admin-content">
        @yield('breadcrumb')
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('build/assets/admin-CjUbDXxY.js') }}" type="module"></script>
    
    @stack('scripts')
</body>
</html>