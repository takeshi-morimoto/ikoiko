@extends('layouts.app')

@section('title', 'ページが見つかりません | KOIKOI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="error-page">
                <div class="error-icon mb-4">
                    <i class="fas fa-search" style="font-size: 100px; color: #4A90E2;"></i>
                </div>
                
                <h1 class="display-1 fw-bold text-muted">404</h1>
                
                <h2 class="mb-4">お探しのページが見つかりません</h2>
                
                <p class="text-muted mb-5">
                    申し訳ございません。お探しのページは移動または削除された可能性があります。<br>
                    URLをご確認いただくか、下記のリンクからお探しのページをお選びください。
                </p>
                
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mb-5">
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>
                        トップページへ
                    </a>
                    <a href="{{ route('anime.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-star me-2"></i>
                        アニメコン一覧
                    </a>
                    <a href="{{ route('machi.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-city me-2"></i>
                        街コン一覧
                    </a>
                </div>
                
                <div class="search-suggestion">
                    <p class="text-muted mb-3">または、イベントを検索してみてください</p>
                    <form action="{{ route('home') }}" method="GET" class="d-flex gap-2 justify-content-center">
                        <input type="text" name="search" class="form-control" placeholder="イベント名で検索" style="max-width: 300px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .error-page {
        padding: 40px 0;
    }
    
    .error-icon {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-20px);
        }
    }
    
    .display-1 {
        font-size: 120px;
        line-height: 1;
    }
    
    @media (max-width: 768px) {
        .display-1 {
            font-size: 80px;
        }
        .error-icon i {
            font-size: 80px !important;
        }
    }
</style>
@endsection